<?php

/*
 | (c) copyright 2024 - MillionVisions
 */

namespace MillionVisions\LaravelI18n\Enums;

/**
 * IETF Language Tags
 *
 * Enum representing a wide range of IETF language tags.
 */
enum IETFLanguageTag: string
{
    case AM    = 'am';      // Amharic
    case AM_ET = 'am-ET';   // Amharic (Ethiopia)
    case AR    = 'ar';      // Arabic
    case AR_SA = 'ar-SA';   // Arabic (Saudi Arabia)
    case AR_EG = 'ar-EG';   // Arabic (Egypt)
    case AR_AE = 'ar-AE';   // Arabic (United Arab Emirates)
    case AR_IQ = 'ar-IQ';   // Arabic (Iraq)
    case AR_JO = 'ar-JO';   // Arabic (Jordan)
    case AR_KW = 'ar-KW';   // Arabic (Kuwait)
    case AR_LB = 'ar-LB';   // Arabic (Lebanon)
    case AR_LY = 'ar-LY';   // Arabic (Libya)
    case AR_MA = 'ar-MA';   // Arabic (Morocco)
    case AR_OM = 'ar-OM';   // Arabic (Oman)
    case AR_QA = 'ar-QA';   // Arabic (Qatar)
    case AR_SY = 'ar-SY';   // Arabic (Syria)
    case AR_TN = 'ar-TN';   // Arabic (Tunisia)
    case AR_YE = 'ar-YE';   // Arabic (Yemen)
    case BN    = 'bn';      // Bengali
    case BN_IN = 'bn-IN';   // Bengali (India)
    case BG    = 'bg';      // Bulgarian
    case BG_BG = 'bg-BG';   // Bulgarian (Bulgaria)
    case ZH    = 'zh';      // Chinese
    case ZH_CN = 'zh-CN';   // Chinese (China)
    case ZH_HK = 'zh-HK';   // Chinese (Hong Kong)
    case ZH_TW = 'zh-TW';   // Chinese (Taiwan)
    case ZH_MO = 'zh-MO';   // Chinese (Macao)
    case ZH_SG = 'zh-SG';   // Chinese (Singapore)
    case CS    = 'cs';      // Czech
    case CS_CZ = 'cs-CZ';   // Czech (Czech Republic)
    case DA    = 'da';      // Danish
    case DA_DK = 'da-DK';   // Danish (Denmark)
    case NL    = 'nl';      // Dutch
    case NL_NL = 'nl-NL';   // Dutch (Netherlands)
    case EN    = 'en';      // English
    case EN_US = 'en-US';   // English (United States)
    case EN_GB = 'en-GB';   // English (United Kingdom)
    case EN_AU = 'en-AU';   // English (Australia)
    case EN_CA = 'en-CA';   // English (Canada)
    case EN_NZ = 'en-NZ';   // English (New Zealand)
    case EN_IE = 'en-IE';   // English (Ireland)
    case EN_SG = 'en-SG';   // English (Singapore)
    case EN_ZA = 'en-ZA';   // English (South Africa)
    case FI    = 'fi';      // Finnish
    case FI_FI = 'fi-FI';   // Finnish (Finland)
    case FR    = 'fr';      // French
    case FR_FR = 'fr-FR';   // French (France)
    case FR_CA = 'fr-CA';   // French (Canada)
    case FR_BE = 'fr-BE';   // French (Belgium)
    case FR_CH = 'fr-CH';   // French (Switzerland)
    case FR_LU = 'fr-LU';   // French (Luxembourg)
    case DE    = 'de';      // German
    case DE_DE = 'de-DE';   // German (Germany)
    case DE_AT = 'de-AT';   // German (Austria)
    case DE_CH = 'de-CH';   // German (Switzerland)
    case DE_LU = 'de-LU';   // German (Luxembourg)
    case DE_BE = 'de-BE';   // German (Belgium)
    case EL    = 'el';      // Greek
    case EL_GR = 'el-GR';   // Greek (Greece)
    case HI    = 'hi';      // Hindi
    case HI_IN = 'hi-IN';   // Hindi (India)
    case HI_LK = 'hi-LK';   // Hindi (Sri Lanka)
    case HU    = 'hu';      // Hungarian
    case HU_HU = 'hu-HU';   // Hungarian (Hungary)
    case IS    = 'is';      // Icelandic
    case IS_IS = 'is-IS';   // Icelandic (Iceland)
    case IU    = 'iu';      // Inuktitut
    case IU_CA = 'iu-CA';   // Inuktitut (Canada)
    case IT    = 'it';      // Italian
    case IT_IT = 'it-IT';   // Italian (Italy)
    case IT_CH = 'it-CH';   // Italian (Switzerland)
    case JA    = 'ja';      // Japanese
    case JA_JP = 'ja-JP';   // Japanese (Japan)
    case KO    = 'ko';      // Korean
    case KO_KR = 'ko-KR';   // Korean (South Korea)
    case LV    = 'lv';      // Latvian
    case LV_LV = 'lv-LV';   // Latvian (Latvia)
    case LT    = 'lt';      // Lithuanian
    case LT_LT = 'lt-LT';   // Lithuanian (Lithuania)
    case MS    = 'ms';      // Malay
    case MS_MY = 'ms-MY';   // Malay (Malaysia)
    case NO    = 'no';      // Norwegian
    case NO_NO = 'no-NO';   // Norwegian (Norway)
    case FA    = 'fa';         // Persian
    case FA_IR = 'fa-IR';   // Persian (Iran)
    case PL    = 'pl';      // Polish
    case PL_PL = 'pl-PL';   // Polish (Poland)
    case PT    = 'pt';      // Portuguese
    case PT_PT = 'pt-PT';   // Portuguese (Portugal)
    case PT_BR = 'pt-BR';   // Portuguese (Brazil)
    case PT_CH = 'pt-CH';   // Portuguese (Switzerland)
    case PA    = 'pa';      // Punjabi
    case PA_IN = 'pa-IN';   // Punjabi (India)
    case PA_PK = 'pa-PK';   // Punjabi (Pakistan)
    case RO    = 'ro';      // Romanian
    case RO_RO = 'ro-RO';   // Romanian (Romania)
    case RU    = 'ru';      // Russian
    case RU_RU = 'ru-RU';   // Russian (Russia)
    case RU_UA = 'ru-UA';   // Russian (Ukraine)
    case RU_BY = 'ru-BY';   // Russian (Belarus)
    case SR    = 'sr';      // Serbian
    case SR_RS = 'sr-RS';   // Serbian (Serbia)
    case SK    = 'sk';      // Slovak
    case SK_SK = 'sk-SK';   // Slovak (Slovakia)
    case ES    = 'es';      // Spanish
    case ES_ES = 'es-ES';   // Spanish (Spain)
    case ES_MX = 'es-MX';   // Spanish (Mexico)
    case ES_CO = 'es-CO';   // Spanish (Colombia)
    case ES_AR = 'es-AR';   // Spanish (Argentina)
    case ES_CL = 'es-CL';   // Spanish (Chile)
    case ES_PE = 'es-PE';   // Spanish (Peru)
    case ES_VE = 'es-VE';   // Spanish (Venezuela)
    case SW    = 'sw';      // Swahili
    case SW_KE = 'sw-KE';   // Swahili (Kenya)
    case SW_TZ = 'sw-TZ';   // Swahili (Tanzania)
    case SV    = 'sv';      // Swedish
    case SV_SE = 'sv-SE';   // Swedish (Sweden)
    case TL    = 'tl';      // Tagalog
    case TL_PH = 'tl-PH';   // Tagalog (Philippines)
    case TH    = 'th';      // Thai
    case TH_TH = 'th-TH';   // Thai (Thailand)
    case TR    = 'tr';      // Turkish
    case TR_TR = 'tr-TR';   // Turkish (Turkey)
    case VI    = 'vi';      // Vietnamese
    case VI_VN = 'vi-VN';   // Vietnamese (Vietnam)
    case CY    = 'cy';      // Welsh
    case CY_GB = 'cy-GB';   // Welsh (United Kingdom)
    case XH    = 'xh';      // Xhosa
    case XH_ZA = 'xh-ZA';   // Xhosa (South Africa)
    case YO    = 'yo';      // Yoruba
    case YO_NG = 'yo-NG';   // Yoruba (Nigeria)
    case ZU    = 'zu';      // Zulu
    case ZU_ZA = 'zu-ZA';   // Zulu (South Africa)
}
