<?php

/*
 | (c) copyright 2024 - MillionVisions
 */

namespace MillionVisions\LaravelI18n\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

/**
 * CreateTranslationFiles command Class
 *
 * This console command scans the application files for translation keys and automatically generates
 * translation files for all supported locales. It facilitates the maintenance of translation files by
 * parsing project directories, extracting translatable strings, and organizing them into language-specific
 * PHP files. This helps in keeping translations up-to-date across different parts of the application.
 *
 * Key Functionalities:
 *   - Scans directories and files for translation keys using specified file extensions.
 *   - Ignores directories that are not relevant to the translation process, such as `node_modules` and `vendor`.
 *   - Creates translation files for each available locale, ensuring the required directories exist.
 *   - Handles potential conflicts or errors during file creation, logging any issues encountered.
 *
 * Configuration:
 *   - Uses configuration options like `seeder.base_path`, `seeder.file_extensions`, `seeder.ignore_directories`,
 *     and `seeder.target_directory` to customize the scanning and file creation process.
 *
 * Usage:
 *   This command can be executed via the CLI, using `php artisan i18n:create-translation-files`.
 *   It automates the process of seeding new translation files and is ideal for projects with frequent
 *   updates to translatable content.
 */
class CreateTranslationFiles extends Command
{
    /** @var array<int,string> $aliases */
    protected $aliases = [
        'i18n:seed'
    ];
    /** @var string $description */
    protected $description = 'Seed translation files from project files';
    /** @var string $signature */
    protected $signature = 'i18n:create-translation-files';

    /**
     * Regular expressions for extracting translation keys.
     *
     * The keys in the array represent functions that are commonly used for retrieving translations,
     * and the regular expressions are used to match and extract translation keys from the project files.
     *
     * @access protected
     * @var array<string,string>
     */
    protected const array REGULAR_EXPRESSIONS = [
        '__()' => '"/__\(\s*[\'\"]([^\'\"]+)[\'\"]\s*,?.*?\)/"'
    ];

    /**
     * Configuration array.
     *
     * Contains configurations used for determining locales, base paths, and other settings
     * that control how the translation seeding process should work.
     *
     * @access protected
     * @var array<string,mixed>
     */
    protected array $config = [];

    /**
     * Constructor for CreateTranslationFiles.
     *
     * This constructor initializes the command by loading configuration settings
     * that dictate how the translation files will be generated.
     *
     * @access public
     * @constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->config = [
            'available_locales' => Config::get('i18n.available_locales'),
            'base_path' => Config::get('i18n.seeder.base_path'),
            'file_extensions' => Config::get('i18n.seeder.file_extensions'),
            'ignored_directories' => Config::get('i18n.seeder.ignore_directories'),
            'target_directory' => Config::get('i18n.seeder.target-directory')
        ];
    }

    /**
     * Handle the command execution.
     *
     * This method is the entry point for the command. It retrieves directories from the project,
     * parses them for translation keys, and creates corresponding translation files.
     *
     * @access public
     * @return void
     */
    public function handle(): void
    {
        /** @var string[] $directories */
        $directories = $this->getDirectories();
        /** @var string[] $translations */
        $translations = $this->parseDirectoriesForTranslations($directories);

        $this->createFiles($translations);
    }

    /**
     * Create a single translation file.
     *
     * This method generates a PHP file containing an array of translation keys and values.
     * It writes the file to the specified path, handling errors if the file cannot be created.
     *
     * @access protected
     * @param string               $path         The file path where the translation file should be created.
     * @param array<string,string> $translations The translation key-value pairs to write into the file.
     * @return bool Returns true if the file was created successfully, false otherwise.
     */
    protected function createFile(string $path, array $translations): bool
    {
        try {
            File::put(
                $path,
                "<?php\n\nreturn " . $this->writeArray($translations) . ";\n"
            );
        } catch (Exception $e) {
            $this->error($e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * Create translation files for all available locales.
     *
     * This method iterates through all available locales and generates translation files based on
     * the extracted translation keys. It ensures that the necessary directories are created if they
     * do not already exist.
     *
     * @access protected
     * @param array<string,string> $translations The collection of extracted translation keys.
     * @return bool Returns true if all translation files were created successfully, false otherwise.
     */
    protected function createFiles(array $translations): bool
    {
        /** @var string[] $available_locales */
        $available_locales = $this->config['available_locales'];
        /** @var string $target_directory */
        $target_directory = $this->config['target_directory'];

        foreach ($available_locales as $locale) {
            /** @var non-falsy-string $locale_path */
            $locale_path = $target_directory . DIRECTORY_SEPARATOR . $locale;

            if (!File::exists($locale_path)) {
                try {
                    File::makeDirectory($locale_path, 0755, true, true);
                } catch (Exception $e) {
                    $this->error($e->getMessage());

                    return false;
                }
            }

            /** @var array<string,mixed> $files */
            $files = [];

            foreach ($translations as $key => $value) {
                /** @var array<int,string> $key_parts */
                $key_parts = explode('.', $key);
                /** @var string $parent */
                $parent = array_shift($key_parts);
                /** @var string $child */
                $child = implode('.', $key_parts);
                /** @var non-falsy-string $file_path */
                $file_path = $locale_path . DIRECTORY_SEPARATOR . $parent . '.php';
                /** @var array<int,string> $file_content */
                $file_content = File::exists($file_path)
                    ? File::getRequire($file_path)
                    : [];

                $files[$file_path][$child] = data_get($file_content, $child, $value) ?? $value;
            }

            foreach ($files as $path => $translations) {
                $this->createFile($path, $translations);
            }
        }

        return true;
    }

    /**
     * Retrieve and filter directories for parsing.
     *
     * This method fetches all directories within the base path and filters out those that are
     * in the list of ignored directories, ensuring only relevant directories are parsed.
     *
     * @access protected
     * @return array<int,string> The list of directories to be parsed for translation strings.
     */
    protected function getDirectories(): array
    {
        /** @var string $base_path */
        $base_path = $this->config['base_path'];
        /** @var string[] $ignored_directories */
        $ignored_directories = $this->config['ignored_directories'];

        return array_filter(
            File::directories($base_path),
            function ($directory) use ($ignored_directories) {
                foreach ($ignored_directories as $ignored_directory) {
                    if (str_contains($directory, $ignored_directory)) {
                        return false;
                    }
                }

                return true;
            });
    }

    /**
     * Parse a single file for translation strings.
     *
     * This method uses regular expressions to scan the content of a file for translation keys,
     * returning an array of extracted keys that can be used to generate translation files.
     *
     * @access protected
     * @param SplFileInfo $file The file to be scanned for translation strings.
     * @return array<string,array> An array of extracted translation keys.
     */
    protected function parseFileForTranslations(SplFileInfo $file): array
    {
        /** @var string $content */
        $content = $file->getContents();

        return array_merge(
            array_map(
                function ($reg) use ($content, $file) {
                    /** @var array<string,string> $translations */
                    $translations = [];

                    preg_match_all($reg, $content, $matches);

                    foreach ($matches[1] as $key) {
                        if (!$this->validateKey($key)) {
                            $this->error("file: '{$file}', key: '{$key}'");

                            continue;
                        }

                        $translations[$key] = $key;
                    }

                    return $translations;
                },
                self::REGULAR_EXPRESSIONS
            ));
    }

    /**
     * Parse a directory for translation strings.
     *
     * This method scans each file within a directory and extracts translation keys from files
     * with specified extensions, consolidating them for further processing.
     *
     * @access protected
     * @param string $directory The directory to be parsed for translation strings.
     * @return array<string,array> An array of extracted translation keys from the directory.
     */
    protected function parseDirectoryForTranslations(string $directory): array
    {
        /** @var array $file_extensions */
        $file_extensions = $this->config['file_extensions'];
        /** @var SplFileInfo[] $files */
        $files = File::allFiles($directory);

        return array_merge(
            array_map(
                function ($file) use ($file_extensions) {

                    return in_array($file->getExtension(), $file_extensions)
                        ? $this->parseFileForTranslations($file)
                        : [];
                },
                $files
            ));
    }

    /**
     * Parse multiple directories for translation strings.
     *
     * This method processes an array of directories, extracting translation strings
     * from all the files within those directories.
     *
     * @access protected
     * @param array<int,string> $directories The list of directories to be parsed.
     * @return array<string,array> of translation keys extracted from all directories.
     */
    protected function parseDirectoriesForTranslations(array $directories): array
    {

        return array_merge(
            array_map(
                function ($directory) {

                    return $this->parseDirectoryForTranslations($directory);
                },
                $directories
            ));
    }

    /**
     * Validate a translation key.
     *
     * This method checks if the given translation key is valid. A valid key is determined based on specific rules:
     * - It must not contain a period (".") unless it is used as a nested key.
     * - It should not contain spaces, as keys with spaces are considered invalid.
     *
     * This validation helps ensure that keys follow the correct structure, especially when creating translation files.
     *
     * @access protected
     * @param string $key The translation key to validate.
     * @return bool Returns true if the key is valid, false otherwise.
     */
    protected function validateKey(string $key): bool
    {

        return !str_contains($key, '.') || str_contains($key, ' ');
    }

    /**
     * Convert an associative array of translations to a formatted PHP string.
     *
     * This method recursively converts an array of translations into a PHP code string that represents
     * an array. It ensures that the generated string is properly formatted with indentation for readability.
     * This is useful for writing translation files that will be easy to read and maintain.
     *
     * The method uses indentation to visually represent nested arrays, making it clear which keys belong
     * to which parent. Each level of nesting is indented further to improve clarity.
     *
     * @access protected
     * @param array<string,mixed> $translations The associative array of translations to convert.
     * @param int                 $indent       The current level of indentation (used for recursive calls).
     * @return string Returns a PHP string representing the translations array.
     */
    protected function writeArray(array $translations, int $indent = 1): string
    {
        ksort($translations);

        /** @var string[] $lines */
        $lines = [];
        /** @var string $tabs */
        $tabs = str_repeat("\t", $indent);

        foreach ($translations as $translation_key => $translation_value) {
            /** @var string|null $key */
            $key = var_export($translation_key, true);
            /** @var string $value */
            $value = is_array($translation_value)
                ? $this->writeArray($translation_value, $indent + 1)
                : var_export($translation_value, true);

            $lines[] = "{$tabs}{$key} => {$value}";
        }

        /** @var string $tabs */
        $tabs = str_repeat("\t", $indent - 1);

        return "[\n" . implode(",\n", $lines) . "\n{$tabs}]";
    }
}
