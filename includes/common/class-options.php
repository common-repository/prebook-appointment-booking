<?php
/**
 * Prebook Options class.
 *
 * @package Prebook
 * @since 1.0.0
 */

// Prebook namespace.
namespace Prebook;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . '\Options' ) ) {
	/**
	 * Prebook Settings class.
	 *
	 * @package Prebook
	 * @since 1.0.0
	 */
	class Options extends \Prebook\Base {

		/**
		 * Get options.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_options() {
			return [
				'business_types' => $this->get_business_types(),
				'countries'      => $this->get_countries(),
				'general_fields'        => $this->get_general_fields(),
				'appointment_statuses'  => $this->get_appointment_statuses(),
				'payment_methods'       => $this->get_payment_methods(),
				'appointment_types'     => $this->get_appointment_types(),
				'color_sets'            => $this->get_color_sets(),
				'email_placeholders'    => $this->get_email_placeholders(),
				'fonts'                 => $this->get_fonts(),
			];
		}

		/**
		 * Business types.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_business_types() {

			$types = [
				'accountants' => __( 'Accountants', 'prebook' ),
				'artist' => __( 'Artist', 'prebook' ),
				'automotive_services' => __( 'Automotive Services', 'prebook' ),
				'beauty_wellness' => __( 'Beauty & Wellness', 'prebook' ),
				'consultant' => __( 'Consultant', 'prebook' ),
				'dietitians' => __( 'Dietitians', 'prebook' ),
				'education_center' => __( 'Education Center', 'prebook' ),
				'entertainment' => __( 'Entertainment', 'prebook' ),
				'event_planning' => __( 'Event Planning', 'prebook' ),
				'fitness_gyms' => __( 'Fitness & Gyms', 'prebook' ),
				'freelancer' => __( 'Freelancer', 'prebook' ),
				'health_medical' => __( 'Health & Medical', 'prebook' ),
				'home_services' => __( 'Home Services', 'prebook' ),
				'hospitality_travel' => __( 'Hospitality & Travel', 'prebook' ),
				'instructor' => __( 'Instructor', 'prebook' ),
				'law_firms' => __( 'Law Firms', 'prebook' ),
				'lawyers' => __( 'Lawyers', 'prebook' ),
				'makeup_artist' => __( 'Makeup Artist', 'prebook' ),
				'marketing_agency' => __( 'Marketing Agency', 'prebook' ),
				'nutritionist' => __( 'Nutritionist', 'prebook' ),
				'personal_trainer' => __( 'Personal Trainer', 'prebook' ),
				'pet_services' => __( 'Pet Services', 'prebook' ),
				'photographer' => __( 'Photographer', 'prebook' ),
				'physiotherapist' => __( 'Physiotherapist', 'prebook' ),
				'psychotherapists' => __( 'Psychotherapists', 'prebook' ),
				'real_estate_agency' => __( 'Real Estate Agency', 'prebook' ),
				'tax_advisors' => __( 'Tax Advisors', 'prebook' ),
				'training_center' => __( 'Training Center', 'prebook' ),
				'sports_gaming' => __( 'Sports & Gaming', 'prebook' ),
				'other' => __( 'Other', 'prebook' ),

			];

			return apply_filters( 'prebook_business_types', $types );
		}

		/**
		 * Countries.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_countries() {

			$countries = array(
				array(
					'name' => 'Afghanistan',
					'code' => 'AF',
					'phone' => 93,
					'symbol' => '؋',
					'currency' => 'AFN',
				),
				array(
					'name' => 'Aland Islands',
					'code' => 'AX',
					'phone' => 358,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Albania',
					'code' => 'AL',
					'phone' => 355,
					'symbol' => 'Lek',
					'currency' => 'ALL',
				),
				array(
					'name' => 'Algeria',
					'code' => 'DZ',
					'phone' => 213,
					'symbol' => 'دج',
					'currency' => 'DZD',
				),
				array(
					'name' => 'American Samoa',
					'code' => 'AS',
					'phone' => 1684,
					'symbol' => '$',
					'currency' => 'USD',
				),
				array(
					'name' => 'Andorra',
					'code' => 'AD',
					'phone' => 376,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Angola',
					'code' => 'AO',
					'phone' => 244,
					'symbol' => 'Kz',
					'currency' => 'AOA',
				),
				array(
					'name' => 'Anguilla',
					'code' => 'AI',
					'phone' => 1264,
					'symbol' => '$',
					'currency' => 'XCD',
				),
				array(
					'name' => 'Antarctica',
					'code' => 'AQ',
					'phone' => 672,
					'symbol' => '$',
					'currency' => 'AAD',
				),
				array(
					'name' => 'Antigua and Barbuda',
					'code' => 'AG',
					'phone' => 1268,
					'symbol' => '$',
					'currency' => 'XCD',
				),
				array(
					'name' => 'Argentina',
					'code' => 'AR',
					'phone' => 54,
					'symbol' => '$',
					'currency' => 'ARS',
				),
				array(
					'name' => 'Armenia',
					'code' => 'AM',
					'phone' => 374,
					'symbol' => '֏',
					'currency' => 'AMD',
				),
				array(
					'name' => 'Aruba',
					'code' => 'AW',
					'phone' => 297,
					'symbol' => 'ƒ',
					'currency' => 'AWG',
				),
				array(
					'name' => 'Australia',
					'code' => 'AU',
					'phone' => 61,
					'symbol' => '$',
					'currency' => 'AUD',
				),
				array(
					'name' => 'Austria',
					'code' => 'AT',
					'phone' => 43,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Azerbaijan',
					'code' => 'AZ',
					'phone' => 994,
					'symbol' => 'm',
					'currency' => 'AZN',
				),
				array(
					'name' => 'Bahamas',
					'code' => 'BS',
					'phone' => 1242,
					'symbol' => 'B$',
					'currency' => 'BSD',
				),
				array(
					'name' => 'Bahrain',
					'code' => 'BH',
					'phone' => 973,
					'symbol' => '.د.ب',
					'currency' => 'BHD',
				),
				array(
					'name' => 'Bangladesh',
					'code' => 'BD',
					'phone' => 880,
					'symbol' => '৳',
					'currency' => 'BDT',
				),
				array(
					'name' => 'Barbados',
					'code' => 'BB',
					'phone' => 1246,
					'symbol' => 'Bds$',
					'currency' => 'BBD',
				),
				array(
					'name' => 'Belarus',
					'code' => 'BY',
					'phone' => 375,
					'symbol' => 'Br',
					'currency' => 'BYN',
				),
				array(
					'name' => 'Belgium',
					'code' => 'BE',
					'phone' => 32,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Belize',
					'code' => 'BZ',
					'phone' => 501,
					'symbol' => '$',
					'currency' => 'BZD',
				),
				array(
					'name' => 'Benin',
					'code' => 'BJ',
					'phone' => 229,
					'symbol' => 'CFA',
					'currency' => 'XOF',
				),
				array(
					'name' => 'Bermuda',
					'code' => 'BM',
					'phone' => 1441,
					'symbol' => '$',
					'currency' => 'BMD',
				),
				array(
					'name' => 'Bhutan',
					'code' => 'BT',
					'phone' => 975,
					'symbol' => 'Nu.',
					'currency' => 'BTN',
				),
				array(
					'name' => 'Bolivia',
					'code' => 'BO',
					'phone' => 591,
					'symbol' => 'Bs.',
					'currency' => 'BOB',
				),
				array(
					'name' => 'Bonaire, Sint Eustatius and Saba',
					'code' => 'BQ',
					'phone' => 599,
					'symbol' => '$',
					'currency' => 'USD',
				),
				array(
					'name' => 'Bosnia and Herzegovina',
					'code' => 'BA',
					'phone' => 387,
					'symbol' => 'KM',
					'currency' => 'BAM',
				),
				array(
					'name' => 'Botswana',
					'code' => 'BW',
					'phone' => 267,
					'symbol' => 'P',
					'currency' => 'BWP',
				),
				array(
					'name' => 'Bouvet Island',
					'code' => 'BV',
					'phone' => 55,
					'symbol' => 'kr',
					'currency' => 'NOK',
				),
				array(
					'name' => 'Brazil',
					'code' => 'BR',
					'phone' => 55,
					'symbol' => 'R$',
					'currency' => 'BRL',
				),
				array(
					'name' => 'British Indian Ocean Territory',
					'code' => 'IO',
					'phone' => 246,
					'symbol' => '$',
					'currency' => 'USD',
				),
				array(
					'name' => 'Brunei Darussalam',
					'code' => 'BN',
					'phone' => 673,
					'symbol' => 'B$',
					'currency' => 'BND',
				),
				array(
					'name' => 'Bulgaria',
					'code' => 'BG',
					'phone' => 359,
					'symbol' => 'Лв.',
					'currency' => 'BGN',
				),
				array(
					'name' => 'Burkina Faso',
					'code' => 'BF',
					'phone' => 226,
					'symbol' => 'CFA',
					'currency' => 'XOF',
				),
				array(
					'name' => 'Burundi',
					'code' => 'BI',
					'phone' => 257,
					'symbol' => 'FBu',
					'currency' => 'BIF',
				),
				array(
					'name' => 'Cambodia',
					'code' => 'KH',
					'phone' => 855,
					'symbol' => 'KHR',
					'currency' => 'KHR',
				),
				array(
					'name' => 'Cameroon',
					'code' => 'CM',
					'phone' => 237,
					'symbol' => 'FCFA',
					'currency' => 'XAF',
				),
				array(
					'name' => 'Canada',
					'code' => 'CA',
					'phone' => 1,
					'symbol' => '$',
					'currency' => 'CAD',
				),
				array(
					'name' => 'Cape Verde',
					'code' => 'CV',
					'phone' => 238,
					'symbol' => '$',
					'currency' => 'CVE',
				),
				array(
					'name' => 'Cayman Islands',
					'code' => 'KY',
					'phone' => 1345,
					'symbol' => '$',
					'currency' => 'KYD',
				),
				array(
					'name' => 'Central African Republic',
					'code' => 'CF',
					'phone' => 236,
					'symbol' => 'FCFA',
					'currency' => 'XAF',
				),
				array(
					'name' => 'Chad',
					'code' => 'TD',
					'phone' => 235,
					'symbol' => 'FCFA',
					'currency' => 'XAF',
				),
				array(
					'name' => 'Chile',
					'code' => 'CL',
					'phone' => 56,
					'symbol' => '$',
					'currency' => 'CLP',
				),
				array(
					'name' => 'China',
					'code' => 'CN',
					'phone' => 86,
					'symbol' => '¥',
					'currency' => 'CNY',
				),
				array(
					'name' => 'Christmas Island',
					'code' => 'CX',
					'phone' => 61,
					'symbol' => '$',
					'currency' => 'AUD',
				),
				array(
					'name' => 'Cocos (Keeling) Islands',
					'code' => 'CC',
					'phone' => 672,
					'symbol' => '$',
					'currency' => 'AUD',
				),
				array(
					'name' => 'Colombia',
					'code' => 'CO',
					'phone' => 57,
					'symbol' => '$',
					'currency' => 'COP',
				),
				array(
					'name' => 'Comoros',
					'code' => 'KM',
					'phone' => 269,
					'symbol' => 'CF',
					'currency' => 'KMF',
				),
				array(
					'name' => 'Congo',
					'code' => 'CG',
					'phone' => 242,
					'symbol' => 'FC',
					'currency' => 'XAF',
				),
				array(
					'name' => 'Congo, Democratic Republic of the Congo',
					'code' => 'CD',
					'phone' => 242,
					'symbol' => 'FC',
					'currency' => 'CDF',
				),
				array(
					'name' => 'Cook Islands',
					'code' => 'CK',
					'phone' => 682,
					'symbol' => '$',
					'currency' => 'NZD',
				),
				array(
					'name' => 'Costa Rica',
					'code' => 'CR',
					'phone' => 506,
					'symbol' => '₡',
					'currency' => 'CRC',
				),
				array(
					'name' => "Cote D'Ivoire",
					'code' => 'CI',
					'phone' => 225,
					'symbol' => 'CFA',
					'currency' => 'XOF',
				),
				array(
					'name' => 'Croatia',
					'code' => 'HR',
					'phone' => 385,
					'symbol' => 'kn',
					'currency' => 'HRK',
				),
				array(
					'name' => 'Cuba',
					'code' => 'CU',
					'phone' => 53,
					'symbol' => '$',
					'currency' => 'CUP',
				),
				array(
					'name' => 'Curacao',
					'code' => 'CW',
					'phone' => 599,
					'symbol' => 'ƒ',
					'currency' => 'ANG',
				),
				array(
					'name' => 'Cyprus',
					'code' => 'CY',
					'phone' => 357,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Czech Republic',
					'code' => 'CZ',
					'phone' => 420,
					'symbol' => 'Kč',
					'currency' => 'CZK',
				),
				array(
					'name' => 'Denmark',
					'code' => 'DK',
					'phone' => 45,
					'symbol' => 'Kr.',
					'currency' => 'DKK',
				),
				array(
					'name' => 'Djibouti',
					'code' => 'DJ',
					'phone' => 253,
					'symbol' => 'Fdj',
					'currency' => 'DJF',
				),
				array(
					'name' => 'Dominica',
					'code' => 'DM',
					'phone' => 1767,
					'symbol' => '$',
					'currency' => 'XCD',
				),
				array(
					'name' => 'Dominican Republic',
					'code' => 'DO',
					'phone' => 1809,
					'symbol' => '$',
					'currency' => 'DOP',
				),
				array(
					'name' => 'Ecuador',
					'code' => 'EC',
					'phone' => 593,
					'symbol' => '$',
					'currency' => 'USD',
				),
				array(
					'name' => 'Egypt',
					'code' => 'EG',
					'phone' => 20,
					'symbol' => 'ج.م',
					'currency' => 'EGP',
				),
				array(
					'name' => 'El Salvador',
					'code' => 'SV',
					'phone' => 503,
					'symbol' => '$',
					'currency' => 'USD',
				),
				array(
					'name' => 'Equatorial Guinea',
					'code' => 'GQ',
					'phone' => 240,
					'symbol' => 'FCFA',
					'currency' => 'XAF',
				),
				array(
					'name' => 'Eritrea',
					'code' => 'ER',
					'phone' => 291,
					'symbol' => 'Nfk',
					'currency' => 'ERN',
				),
				array(
					'name' => 'Estonia',
					'code' => 'EE',
					'phone' => 372,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Ethiopia',
					'code' => 'ET',
					'phone' => 251,
					'symbol' => 'Nkf',
					'currency' => 'ETB',
				),
				array(
					'name' => 'Falkland Islands (Malvinas)',
					'code' => 'FK',
					'phone' => 500,
					'symbol' => '£',
					'currency' => 'FKP',
				),
				array(
					'name' => 'Faroe Islands',
					'code' => 'FO',
					'phone' => 298,
					'symbol' => 'Kr.',
					'currency' => 'DKK',
				),
				array(
					'name' => 'Fiji',
					'code' => 'FJ',
					'phone' => 679,
					'symbol' => 'FJ$',
					'currency' => 'FJD',
				),
				array(
					'name' => 'Finland',
					'code' => 'FI',
					'phone' => 358,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'France',
					'code' => 'FR',
					'phone' => 33,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'French Guiana',
					'code' => 'GF',
					'phone' => 594,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'French Polynesia',
					'code' => 'PF',
					'phone' => 689,
					'symbol' => '₣',
					'currency' => 'XPF',
				),
				array(
					'name' => 'French Southern Territories',
					'code' => 'TF',
					'phone' => 262,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Gabon',
					'code' => 'GA',
					'phone' => 241,
					'symbol' => 'FCFA',
					'currency' => 'XAF',
				),
				array(
					'name' => 'Gambia',
					'code' => 'GM',
					'phone' => 220,
					'symbol' => 'D',
					'currency' => 'GMD',
				),
				array(
					'name' => 'Georgia',
					'code' => 'GE',
					'phone' => 995,
					'symbol' => 'ლ',
					'currency' => 'GEL',
				),
				array(
					'name' => 'Germany',
					'code' => 'DE',
					'phone' => 49,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Ghana',
					'code' => 'GH',
					'phone' => 233,
					'symbol' => 'GH₵',
					'currency' => 'GHS',
				),
				array(
					'name' => 'Gibraltar',
					'code' => 'GI',
					'phone' => 350,
					'symbol' => '£',
					'currency' => 'GIP',
				),
				array(
					'name' => 'Greece',
					'code' => 'GR',
					'phone' => 30,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Greenland',
					'code' => 'GL',
					'phone' => 299,
					'symbol' => 'Kr.',
					'currency' => 'DKK',
				),
				array(
					'name' => 'Grenada',
					'code' => 'GD',
					'phone' => 1473,
					'symbol' => '$',
					'currency' => 'XCD',
				),
				array(
					'name' => 'Guadeloupe',
					'code' => 'GP',
					'phone' => 590,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Guam',
					'code' => 'GU',
					'phone' => 1671,
					'symbol' => '$',
					'currency' => 'USD',
				),
				array(
					'name' => 'Guatemala',
					'code' => 'GT',
					'phone' => 502,
					'symbol' => 'Q',
					'currency' => 'GTQ',
				),
				array(
					'name' => 'Guernsey',
					'code' => 'GG',
					'phone' => 44,
					'symbol' => '£',
					'currency' => 'GBP',
				),
				array(
					'name' => 'Guinea',
					'code' => 'GN',
					'phone' => 224,
					'symbol' => 'FG',
					'currency' => 'GNF',
				),
				array(
					'name' => 'Guinea-Bissau',
					'code' => 'GW',
					'phone' => 245,
					'symbol' => 'CFA',
					'currency' => 'XOF',
				),
				array(
					'name' => 'Guyana',
					'code' => 'GY',
					'phone' => 592,
					'symbol' => '$',
					'currency' => 'GYD',
				),
				array(
					'name' => 'Haiti',
					'code' => 'HT',
					'phone' => 509,
					'symbol' => 'G',
					'currency' => 'HTG',
				),
				array(
					'name' => 'Heard Island and McDonald Islands',
					'code' => 'HM',
					'phone' => 0,
					'symbol' => '$',
					'currency' => 'AUD',
				),
				array(
					'name' => 'Holy See (Vatican City State)',
					'code' => 'VA',
					'phone' => 39,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Honduras',
					'code' => 'HN',
					'phone' => 504,
					'symbol' => 'L',
					'currency' => 'HNL',
				),
				array(
					'name' => 'Hong Kong',
					'code' => 'HK',
					'phone' => 852,
					'symbol' => '$',
					'currency' => 'HKD',
				),
				array(
					'name' => 'Hungary',
					'code' => 'HU',
					'phone' => 36,
					'symbol' => 'Ft',
					'currency' => 'HUF',
				),
				array(
					'name' => 'Iceland',
					'code' => 'IS',
					'phone' => 354,
					'symbol' => 'kr',
					'currency' => 'ISK',
				),
				array(
					'name' => 'India',
					'code' => 'IN',
					'phone' => 91,
					'symbol' => '₹',
					'currency' => 'INR',
				),
				array(
					'name' => 'Indonesia',
					'code' => 'ID',
					'phone' => 62,
					'symbol' => 'Rp',
					'currency' => 'IDR',
				),
				array(
					'name' => 'Iran, Islamic Republic of',
					'code' => 'IR',
					'phone' => 98,
					'symbol' => '﷼',
					'currency' => 'IRR',
				),
				array(
					'name' => 'Iraq',
					'code' => 'IQ',
					'phone' => 964,
					'symbol' => 'د.ع',
					'currency' => 'IQD',
				),
				array(
					'name' => 'Ireland',
					'code' => 'IE',
					'phone' => 353,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Isle of Man',
					'code' => 'IM',
					'phone' => 44,
					'symbol' => '£',
					'currency' => 'GBP',
				),
				array(
					'name' => 'Israel',
					'code' => 'IL',
					'phone' => 972,
					'symbol' => '₪',
					'currency' => 'ILS',
				),
				array(
					'name' => 'Italy',
					'code' => 'IT',
					'phone' => 39,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Jamaica',
					'code' => 'JM',
					'phone' => 1876,
					'symbol' => 'J$',
					'currency' => 'JMD',
				),
				array(
					'name' => 'Japan',
					'code' => 'JP',
					'phone' => 81,
					'symbol' => '¥',
					'currency' => 'JPY',
				),
				array(
					'name' => 'Jersey',
					'code' => 'JE',
					'phone' => 44,
					'symbol' => '£',
					'currency' => 'GBP',
				),
				array(
					'name' => 'Jordan',
					'code' => 'JO',
					'phone' => 962,
					'symbol' => 'ا.د',
					'currency' => 'JOD',
				),
				array(
					'name' => 'Kazakhstan',
					'code' => 'KZ',
					'phone' => 7,
					'symbol' => 'лв',
					'currency' => 'KZT',
				),
				array(
					'name' => 'Kenya',
					'code' => 'KE',
					'phone' => 254,
					'symbol' => 'KSh',
					'currency' => 'KES',
				),
				array(
					'name' => 'Kiribati',
					'code' => 'KI',
					'phone' => 686,
					'symbol' => '$',
					'currency' => 'AUD',
				),
				array(
					'name' => "Korea, Democratic People's Republic of",
					'code' => 'KP',
					'phone' => 850,
					'symbol' => '₩',
					'currency' => 'KPW',
				),
				array(
					'name' => 'Korea, Republic of',
					'code' => 'KR',
					'phone' => 82,
					'symbol' => '₩',
					'currency' => 'KRW',
				),
				array(
					'name' => 'Kosovo',
					'code' => 'XK',
					'phone' => 383,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Kuwait',
					'code' => 'KW',
					'phone' => 965,
					'symbol' => 'ك.د',
					'currency' => 'KWD',
				),
				array(
					'name' => 'Kyrgyzstan',
					'code' => 'KG',
					'phone' => 996,
					'symbol' => 'лв',
					'currency' => 'KGS',
				),
				array(
					'name' => "Lao People's Democratic Republic",
					'code' => 'LA',
					'phone' => 856,
					'symbol' => '₭',
					'currency' => 'LAK',
				),
				array(
					'name' => 'Latvia',
					'code' => 'LV',
					'phone' => 371,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Lebanon',
					'code' => 'LB',
					'phone' => 961,
					'symbol' => '£',
					'currency' => 'LBP',
				),
				array(
					'name' => 'Lesotho',
					'code' => 'LS',
					'phone' => 266,
					'symbol' => 'L',
					'currency' => 'LSL',
				),
				array(
					'name' => 'Liberia',
					'code' => 'LR',
					'phone' => 231,
					'symbol' => '$',
					'currency' => 'LRD',
				),
				array(
					'name' => 'Libyan Arab Jamahiriya',
					'code' => 'LY',
					'phone' => 218,
					'symbol' => 'د.ل',
					'currency' => 'LYD',
				),
				array(
					'name' => 'Liechtenstein',
					'code' => 'LI',
					'phone' => 423,
					'symbol' => 'CHf',
					'currency' => 'CHF',
				),
				array(
					'name' => 'Lithuania',
					'code' => 'LT',
					'phone' => 370,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Luxembourg',
					'code' => 'LU',
					'phone' => 352,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Macao',
					'code' => 'MO',
					'phone' => 853,
					'symbol' => '$',
					'currency' => 'MOP',
				),
				array(
					'name' => 'Macedonia, the Former Yugoslav Republic of',
					'code' => 'MK',
					'phone' => 389,
					'symbol' => 'ден',
					'currency' => 'MKD',
				),
				array(
					'name' => 'Madagascar',
					'code' => 'MG',
					'phone' => 261,
					'symbol' => 'Ar',
					'currency' => 'MGA',
				),
				array(
					'name' => 'Malawi',
					'code' => 'MW',
					'phone' => 265,
					'symbol' => 'MK',
					'currency' => 'MWK',
				),
				array(
					'name' => 'Malaysia',
					'code' => 'MY',
					'phone' => 60,
					'symbol' => 'RM',
					'currency' => 'MYR',
				),
				array(
					'name' => 'Maldives',
					'code' => 'MV',
					'phone' => 960,
					'symbol' => 'Rf',
					'currency' => 'MVR',
				),
				array(
					'name' => 'Mali',
					'code' => 'ML',
					'phone' => 223,
					'symbol' => 'CFA',
					'currency' => 'XOF',
				),
				array(
					'name' => 'Malta',
					'code' => 'MT',
					'phone' => 356,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Marshall Islands',
					'code' => 'MH',
					'phone' => 692,
					'symbol' => '$',
					'currency' => 'USD',
				),
				array(
					'name' => 'Martinique',
					'code' => 'MQ',
					'phone' => 596,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Mauritania',
					'code' => 'MR',
					'phone' => 222,
					'symbol' => 'MRU',
					'currency' => 'MRO',
				),
				array(
					'name' => 'Mauritius',
					'code' => 'MU',
					'phone' => 230,
					'symbol' => '₨',
					'currency' => 'MUR',
				),
				array(
					'name' => 'Mayotte',
					'code' => 'YT',
					'phone' => 262,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Mexico',
					'code' => 'MX',
					'phone' => 52,
					'symbol' => '$',
					'currency' => 'MXN',
				),
				array(
					'name' => 'Micronesia, Federated States of',
					'code' => 'FM',
					'phone' => 691,
					'symbol' => '$',
					'currency' => 'USD',
				),
				array(
					'name' => 'Moldova, Republic of',
					'code' => 'MD',
					'phone' => 373,
					'symbol' => 'L',
					'currency' => 'MDL',
				),
				array(
					'name' => 'Monaco',
					'code' => 'MC',
					'phone' => 377,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Mongolia',
					'code' => 'MN',
					'phone' => 976,
					'symbol' => '₮',
					'currency' => 'MNT',
				),
				array(
					'name' => 'Montenegro',
					'code' => 'ME',
					'phone' => 382,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Montserrat',
					'code' => 'MS',
					'phone' => 1664,
					'symbol' => '$',
					'currency' => 'XCD',
				),
				array(
					'name' => 'Morocco',
					'code' => 'MA',
					'phone' => 212,
					'symbol' => 'DH',
					'currency' => 'MAD',
				),
				array(
					'name' => 'Mozambique',
					'code' => 'MZ',
					'phone' => 258,
					'symbol' => 'MT',
					'currency' => 'MZN',
				),
				array(
					'name' => 'Myanmar',
					'code' => 'MM',
					'phone' => 95,
					'symbol' => 'K',
					'currency' => 'MMK',
				),
				array(
					'name' => 'Namibia',
					'code' => 'NA',
					'phone' => 264,
					'symbol' => '$',
					'currency' => 'NAD',
				),
				array(
					'name' => 'Nauru',
					'code' => 'NR',
					'phone' => 674,
					'symbol' => '$',
					'currency' => 'AUD',
				),
				array(
					'name' => 'Nepal',
					'code' => 'NP',
					'phone' => 977,
					'symbol' => '₨',
					'currency' => 'NPR',
				),
				array(
					'name' => 'Netherlands',
					'code' => 'NL',
					'phone' => 31,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Netherlands Antilles',
					'code' => 'AN',
					'phone' => 599,
					'symbol' => 'NAf',
					'currency' => 'ANG',
				),
				array(
					'name' => 'New Caledonia',
					'code' => 'NC',
					'phone' => 687,
					'symbol' => '₣',
					'currency' => 'XPF',
				),
				array(
					'name' => 'New Zealand',
					'code' => 'NZ',
					'phone' => 64,
					'symbol' => '$',
					'currency' => 'NZD',
				),
				array(
					'name' => 'Nicaragua',
					'code' => 'NI',
					'phone' => 505,
					'symbol' => 'C$',
					'currency' => 'NIO',
				),
				array(
					'name' => 'Niger',
					'code' => 'NE',
					'phone' => 227,
					'symbol' => 'CFA',
					'currency' => 'XOF',
				),
				array(
					'name' => 'Nigeria',
					'code' => 'NG',
					'phone' => 234,
					'symbol' => '₦',
					'currency' => 'NGN',
				),
				array(
					'name' => 'Niue',
					'code' => 'NU',
					'phone' => 683,
					'symbol' => '$',
					'currency' => 'NZD',
				),
				array(
					'name' => 'Norfolk Island',
					'code' => 'NF',
					'phone' => 672,
					'symbol' => '$',
					'currency' => 'AUD',
				),
				array(
					'name' => 'Northern Mariana Islands',
					'code' => 'MP',
					'phone' => 1670,
					'symbol' => '$',
					'currency' => 'USD',
				),
				array(
					'name' => 'Norway',
					'code' => 'NO',
					'phone' => 47,
					'symbol' => 'kr',
					'currency' => 'NOK',
				),
				array(
					'name' => 'Oman',
					'code' => 'OM',
					'phone' => 968,
					'symbol' => '.ع.ر',
					'currency' => 'OMR',
				),
				array(
					'name' => 'Pakistan',
					'code' => 'PK',
					'phone' => 92,
					'symbol' => '₨',
					'currency' => 'PKR',
				),
				array(
					'name' => 'Palau',
					'code' => 'PW',
					'phone' => 680,
					'symbol' => '$',
					'currency' => 'USD',
				),
				array(
					'name' => 'Palestinian Territory, Occupied',
					'code' => 'PS',
					'phone' => 970,
					'symbol' => '₪',
					'currency' => 'ILS',
				),
				array(
					'name' => 'Panama',
					'code' => 'PA',
					'phone' => 507,
					'symbol' => 'B/.',
					'currency' => 'PAB',
				),
				array(
					'name' => 'Papua New Guinea',
					'code' => 'PG',
					'phone' => 675,
					'symbol' => 'K',
					'currency' => 'PGK',
				),
				array(
					'name' => 'Paraguay',
					'code' => 'PY',
					'phone' => 595,
					'symbol' => '₲',
					'currency' => 'PYG',
				),
				array(
					'name' => 'Peru',
					'code' => 'PE',
					'phone' => 51,
					'symbol' => 'S/.',
					'currency' => 'PEN',
				),
				array(
					'name' => 'Philippines',
					'code' => 'PH',
					'phone' => 63,
					'symbol' => '₱',
					'currency' => 'PHP',
				),
				array(
					'name' => 'Pitcairn',
					'code' => 'PN',
					'phone' => 64,
					'symbol' => '$',
					'currency' => 'NZD',
				),
				array(
					'name' => 'Poland',
					'code' => 'PL',
					'phone' => 48,
					'symbol' => 'zł',
					'currency' => 'PLN',
				),
				array(
					'name' => 'Portugal',
					'code' => 'PT',
					'phone' => 351,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Puerto Rico',
					'code' => 'PR',
					'phone' => 1787,
					'symbol' => '$',
					'currency' => 'USD',
				),
				array(
					'name' => 'Qatar',
					'code' => 'QA',
					'phone' => 974,
					'symbol' => 'ق.ر',
					'currency' => 'QAR',
				),
				array(
					'name' => 'Reunion',
					'code' => 'RE',
					'phone' => 262,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Romania',
					'code' => 'RO',
					'phone' => 40,
					'symbol' => 'lei',
					'currency' => 'RON',
				),
				array(
					'name' => 'Russian Federation',
					'code' => 'RU',
					'phone' => 7,
					'symbol' => '₽',
					'currency' => 'RUB',
				),
				array(
					'name' => 'Rwanda',
					'code' => 'RW',
					'phone' => 250,
					'symbol' => 'FRw',
					'currency' => 'RWF',
				),
				array(
					'name' => 'Saint Barthelemy',
					'code' => 'BL',
					'phone' => 590,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Saint Helena',
					'code' => 'SH',
					'phone' => 290,
					'symbol' => '£',
					'currency' => 'SHP',
				),
				array(
					'name' => 'Saint Kitts and Nevis',
					'code' => 'KN',
					'phone' => 1869,
					'symbol' => '$',
					'currency' => 'XCD',
				),
				array(
					'name' => 'Saint Lucia',
					'code' => 'LC',
					'phone' => 1758,
					'symbol' => '$',
					'currency' => 'XCD',
				),
				array(
					'name' => 'Saint Martin',
					'code' => 'MF',
					'phone' => 590,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Saint Pierre and Miquelon',
					'code' => 'PM',
					'phone' => 508,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Saint Vincent and the Grenadines',
					'code' => 'VC',
					'phone' => 1784,
					'symbol' => '$',
					'currency' => 'XCD',
				),
				array(
					'name' => 'Samoa',
					'code' => 'WS',
					'phone' => 684,
					'symbol' => 'SAT',
					'currency' => 'WST',
				),
				array(
					'name' => 'San Marino',
					'code' => 'SM',
					'phone' => 378,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Sao Tome and Principe',
					'code' => 'ST',
					'phone' => 239,
					'symbol' => 'Db',
					'currency' => 'STD',
				),
				array(
					'name' => 'Saudi Arabia',
					'code' => 'SA',
					'phone' => 966,
					'symbol' => '﷼',
					'currency' => 'SAR',
				),
				array(
					'name' => 'Senegal',
					'code' => 'SN',
					'phone' => 221,
					'symbol' => 'CFA',
					'currency' => 'XOF',
				),
				array(
					'name' => 'Serbia',
					'code' => 'RS',
					'phone' => 381,
					'symbol' => 'din',
					'currency' => 'RSD',
				),
				array(
					'name' => 'Serbia and Montenegro',
					'code' => 'CS',
					'phone' => 381,
					'symbol' => 'din',
					'currency' => 'RSD',
				),
				array(
					'name' => 'Seychelles',
					'code' => 'SC',
					'phone' => 248,
					'symbol' => 'SRe',
					'currency' => 'SCR',
				),
				array(
					'name' => 'Sierra Leone',
					'code' => 'SL',
					'phone' => 232,
					'symbol' => 'Le',
					'currency' => 'SLL',
				),
				array(
					'name' => 'Singapore',
					'code' => 'SG',
					'phone' => 65,
					'symbol' => '$',
					'currency' => 'SGD',
				),
				array(
					'name' => 'St Martin',
					'code' => 'SX',
					'phone' => 721,
					'symbol' => 'ƒ',
					'currency' => 'ANG',
				),
				array(
					'name' => 'Slovakia',
					'code' => 'SK',
					'phone' => 421,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Slovenia',
					'code' => 'SI',
					'phone' => 386,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Solomon Islands',
					'code' => 'SB',
					'phone' => 677,
					'symbol' => 'Si$',
					'currency' => 'SBD',
				),
				array(
					'name' => 'Somalia',
					'code' => 'SO',
					'phone' => 252,
					'symbol' => 'Sh.so.',
					'currency' => 'SOS',
				),
				array(
					'name' => 'South Africa',
					'code' => 'ZA',
					'phone' => 27,
					'symbol' => 'R',
					'currency' => 'ZAR',
				),
				array(
					'name' => 'South Georgia and the South Sandwich Islands',
					'code' => 'GS',
					'phone' => 500,
					'symbol' => '£',
					'currency' => 'GBP',
				),
				array(
					'name' => 'South Sudan',
					'code' => 'SS',
					'phone' => 211,
					'symbol' => '£',
					'currency' => 'SSP',
				),
				array(
					'name' => 'Spain',
					'code' => 'ES',
					'phone' => 34,
					'symbol' => '€',
					'currency' => 'EUR',
				),
				array(
					'name' => 'Sri Lanka',
					'code' => 'LK',
					'phone' => 94,
					'symbol' => 'Rs',
					'currency' => 'LKR',
				),
				array(
					'name' => 'Sudan',
					'code' => 'SD',
					'phone' => 249,
					'symbol' => '.س.ج',
					'currency' => 'SDG',
				),
				array(
					'name' => 'Suriname',
					'code' => 'SR',
					'phone' => 597,
					'symbol' => '$',
					'currency' => 'SRD',
				),
				array(
					'name' => 'Svalbard and Jan Mayen',
					'code' => 'SJ',
					'phone' => 47,
					'symbol' => 'kr',
					'currency' => 'NOK',
				),
				array(
					'name' => 'Swaziland',
					'code' => 'SZ',
					'phone' => 268,
					'symbol' => 'E',
					'currency' => 'SZL',
				),
				array(
					'name' => 'Sweden',
					'code' => 'SE',
					'phone' => 46,
					'symbol' => 'kr',
					'currency' => 'SEK',
				),
				array(
					'name' => 'Switzerland',
					'code' => 'CH',
					'phone' => 41,
					'symbol' => 'CHf',
					'currency' => 'CHF',
				),
				array(
					'name' => 'Syrian Arab Republic',
					'code' => 'SY',
					'phone' => 963,
					'symbol' => 'LS',
					'currency' => 'SYP',
				),
				array(
					'name' => 'Taiwan, Province of China',
					'code' => 'TW',
					'phone' => 886,
					'symbol' => '$',
					'currency' => 'TWD',
				),
				array(
					'name' => 'Tajikistan',
					'code' => 'TJ',
					'phone' => 992,
					'symbol' => 'SM',
					'currency' => 'TJS',
				),
				array(
					'name' => 'Tanzania, United Republic of',
					'code' => 'TZ',
					'phone' => 255,
					'symbol' => 'TSh',
					'currency' => 'TZS',
				),
				array(
					'name' => 'Thailand',
					'code' => 'TH',
					'phone' => 66,
					'symbol' => '฿',
					'currency' => 'THB',
				),
				array(
					'name' => 'Timor-Leste',
					'code' => 'TL',
					'phone' => 670,
					'symbol' => '$',
					'currency' => 'USD',
				),
				array(
					'name' => 'Togo',
					'code' => 'TG',
					'phone' => 228,
					'symbol' => 'CFA',
					'currency' => 'XOF',
				),
				array(
					'name' => 'Tokelau',
					'code' => 'TK',
					'phone' => 690,
					'symbol' => '$',
					'currency' => 'NZD',
				),
				array(
					'name' => 'Tonga',
					'code' => 'TO',
					'phone' => 676,
					'symbol' => '$',
					'currency' => 'TOP',
				),
				array(
					'name' => 'Trinidad and Tobago',
					'code' => 'TT',
					'phone' => 1868,
					'symbol' => '$',
					'currency' => 'TTD',
				),
				array(
					'name' => 'Tunisia',
					'code' => 'TN',
					'phone' => 216,
					'symbol' => 'ت.د',
					'currency' => 'TND',
				),
				array(
					'name' => 'Turkey',
					'code' => 'TR',
					'phone' => 90,
					'symbol' => '₺',
					'currency' => 'TRY',
				),
				array(
					'name' => 'Turkmenistan',
					'code' => 'TM',
					'phone' => 7370,
					'symbol' => 'T',
					'currency' => 'TMT',
				),
				array(
					'name' => 'Turks and Caicos Islands',
					'code' => 'TC',
					'phone' => 1649,
					'symbol' => '$',
					'currency' => 'USD',
				),
				array(
					'name' => 'Tuvalu',
					'code' => 'TV',
					'phone' => 688,
					'symbol' => '$',
					'currency' => 'AUD',
				),
				array(
					'name' => 'Uganda',
					'code' => 'UG',
					'phone' => 256,
					'symbol' => 'USh',
					'currency' => 'UGX',
				),
				array(
					'name' => 'Ukraine',
					'code' => 'UA',
					'phone' => 380,
					'symbol' => '₴',
					'currency' => 'UAH',
				),
				array(
					'name' => 'United Arab Emirates',
					'code' => 'AE',
					'phone' => 971,
					'symbol' => 'إ.د',
					'currency' => 'AED',
				),
				array(
					'name' => 'United Kingdom',
					'code' => 'GB',
					'phone' => 44,
					'symbol' => '£',
					'currency' => 'GBP',
				),
				array(
					'name' => 'United States',
					'code' => 'US',
					'phone' => 1,
					'symbol' => '$',
					'currency' => 'USD',
				),
				array(
					'name' => 'United States Minor Outlying Islands',
					'code' => 'UM',
					'phone' => 1,
					'symbol' => '$',
					'currency' => 'USD',
				),
				array(
					'name' => 'Uruguay',
					'code' => 'UY',
					'phone' => 598,
					'symbol' => '$',
					'currency' => 'UYU',
				),
				array(
					'name' => 'Uzbekistan',
					'code' => 'UZ',
					'phone' => 998,
					'symbol' => 'лв',
					'currency' => 'UZS',
				),
				array(
					'name' => 'Vanuatu',
					'code' => 'VU',
					'phone' => 678,
					'symbol' => 'VT',
					'currency' => 'VUV',
				),
				array(
					'name' => 'Venezuela',
					'code' => 'VE',
					'phone' => 58,
					'symbol' => 'Bs',
					'currency' => 'VEF',
				),
				array(
					'name' => 'Viet Nam',
					'code' => 'VN',
					'phone' => 84,
					'symbol' => '₫',
					'currency' => 'VND',
				),
				array(
					'name' => 'Virgin Islands, British',
					'code' => 'VG',
					'phone' => 1284,
					'symbol' => '$',
					'currency' => 'USD',
				),
				array(
					'name' => 'Virgin Islands, U.s.',
					'code' => 'VI',
					'phone' => 1340,
					'symbol' => '$',
					'currency' => 'USD',
				),
				array(
					'name' => 'Wallis and Futuna',
					'code' => 'WF',
					'phone' => 681,
					'symbol' => '₣',
					'currency' => 'XPF',
				),
				array(
					'name' => 'Western Sahara',
					'code' => 'EH',
					'phone' => 212,
					'symbol' => 'MAD',
					'currency' => 'MAD',
				),
				array(
					'name' => 'Yemen',
					'code' => 'YE',
					'phone' => 967,
					'symbol' => '﷼',
					'currency' => 'YER',
				),
				array(
					'name' => 'Zambia',
					'code' => 'ZM',
					'phone' => 260,
					'symbol' => 'ZK',
					'currency' => 'ZMW',
				),
				array(
					'name' => 'Zimbabwe',
					'code' => 'ZW',
					'phone' => 263,
					'symbol' => '$',
					'currency' => 'ZWL',
				),
			);

			return apply_filters( 'prebook_countries', $countries );
		}

		/**
		 * Get country by code.
		 *
		 * @since 1.0.0
		 * @param string $code
		 * @return array
		 */
		public function get_country_by_code( $code ) {
			$countries = $this->get_countries();

			foreach ( $countries as $country ) {
				if ( $country['code'] === $code ) {
					return $country;
				}
			}

			return [];
		}



		/**
		 * General Settings Fields.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_general_fields() {
			$fields = [
				'reschedule' => [
					'title' => __( 'Allow Reschedule', 'prebook' ),
					'type' => 'checkbox',
					'description' => __( 'Allow customers to reschedule their appointments.', 'prebook' ),
					'default' => false,
				],
				'has_reschedule_limit' => [
					'title' => __( 'Reschedule Limit', 'prebook' ),
					'type' => 'checkbox',
					'description' => __( 'Enable reschedule limit.', 'prebook' ),
					'default' => false,
				],
				'reschedule_limit' => [
					'title' => __( 'Reschedule Limit', 'prebook' ),
					'type' => 'number',
					'description' => __( 'Number of days before the appointment date that the customer can reschedule.', 'prebook' ),
					'default' => 0,
				],
				'reschedule_limit_type' => [
					'title' => __( 'Reschedule Limit Type', 'prebook' ),
					'type' => 'select',
					'options' => [
						'days' => __( 'Days', 'prebook' ),
						'hours' => __( 'Hours', 'prebook' ),
					],
					'default' => 'days',
				],
				'reschedule_limit_message' => [
					'title' => __( 'Reschedule Limit Message', 'prebook' ),
					'type' => 'textarea',
					'description' => __( 'Message to display when the customer has reached the reschedule limit.', 'prebook' ),
					'default' => '',
				],
			];

			return apply_filters( 'prebook_general_settings_fields', $fields );
		}

		/**
		 * Appointment statuses.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_appointment_statuses() {
			$statuses = [
				'approved' => __( 'Approved', 'prebook' ),
				'pending' => __( 'Pending', 'prebook' ),
				'canceled' => __( 'Canceled', 'prebook' ),
				'rejected' => __( 'Rejected', 'prebook' ),
			];

			return apply_filters( 'prebook_appointment_statuses', $statuses );
		}

		/**
		 * Get payment methods.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_payment_methods() {
			$payment_methods = [
				'cash' => __( 'Cash', 'prebook' ),
			];

			return apply_filters( 'prebook_payment_methods', $payment_methods );
		}

		/**
		 * Appointment types.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_appointment_types() {
			$types = [
				'onsite' => __( 'Onsite', 'prebook' ),
				'online' => __( 'Online', 'prebook' ),
				'home_service' => __( 'Home Service', 'prebook' ),
			];

			return apply_filters( 'prebook_appointment_types', $types );
		}

		/**
		 * Get color sets.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_color_sets() {
			$colors = [
				'#2E90FA' => '#FFFFFF',
				'#6172F3' => '#FFFFFF',
				'#875BF7' => '#FFFFFF',
				'#D444F1' => '#FFFFFF',
				'#DD2590' => '#FFFFFF',
				'#EE46BC' => '#FFFFFF',
				'#FF4405' => '#FFFFFF',
				'#E31B54' => '#FFFFFF',
				'#EAAA08' => '#FFFFFF',
				'#F670C7' => '#FFFFFF',
				'#84ADFF' => '#FFFFFF',
				'#FEA3B4' => '#FFFFFF',
				'#FDE272' => '#FFFFFF',
				'#EEAAFD' => '#FFFFFF',

			];

			return apply_filters( 'prebook_color_sets', $colors );
		}


		/**
		 * Email placeholders.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_email_placeholders() {
			$placeholders = [
				'site_name' => __( 'Site Name', 'prebook' ),
				'site_url' => __( 'Site URL', 'prebook' ),

				'appointment_id' => __( 'Appointment ID', 'prebook' ),
				'customer_id' => __( 'Customer ID', 'prebook' ),
				'staff_id' => __( 'Staff ID', 'prebook' ),
				'service_id' => __( 'Service ID', 'prebook' ),
				'booking_date' => __( 'Appointment Date', 'prebook' ),
				'booking_time' => __( 'Appointment Start', 'prebook' ),
				'booking_end_time' => __( 'Appointment End', 'prebook' ),

				'appointment_status' => __( 'Appointment Status', 'prebook' ),
				'staff_name' => __( 'Staff Name', 'prebook' ),
				'staff_email' => __( 'Staff Email', 'prebook' ),
				'staff_phone' => __( 'Staff Phone', 'prebook' ),

				'customer_name' => __( 'Customer Name', 'prebook' ),
				'customer_first_name' => __( 'Customer First Name', 'prebook' ),
				'customer_last_name' => __( 'Customer Last Name', 'prebook' ),
				'customer_email' => __( 'Customer Email', 'prebook' ),
				'customer_phone' => __( 'Customer Phone', 'prebook' ),
				'customer_address' => __( 'Customer Address', 'prebook' ),

				'service_name' => __( 'Service Name', 'prebook' ),
				'service_price' => __( 'Service Price', 'prebook' ),
				'service_duration' => __( 'Service Duration', 'prebook' ),

				'address' => __( 'Address', 'prebook' ),
				'payment_method' => __( 'Payment Method', 'prebook' ),
				'payment_status' => __( 'Payment Status', 'prebook' ),
				'customer_note' => __( 'Customer Note', 'prebook' ),

				'business_logo_url' => __( 'Business Logo URL', 'prebook' ),
				'business_full_logo_url' => __( 'Business Full Logo URL', 'prebook' ),
				'business_name' => __( 'Business Name', 'prebook' ),
				'business_type' => __( 'Business Type', 'prebook' ),
				'business_about' => __( 'Business About', 'prebook' ),
				'business_street' => __( 'Business Street', 'prebook' ),
				'business_city' => __( 'Business City', 'prebook' ),
				'business_state' => __( 'Business State', 'prebook' ),
				'business_zip_code' => __( 'Business Zip Code', 'prebook' ),
				'business_country' => __( 'Business Country Code', 'prebook' ),
				'business_country_name' => __( 'Business Country Name', 'prebook' ),
				'business_phone' => __( 'Business Phone', 'prebook' ),
				'business_website' => __( 'Business Website', 'prebook' ),
				'business_email' => __( 'Business Email', 'prebook' ),
			];

			return apply_filters( 'prebook_email_placeholders', $placeholders );
		}

		/**
		 * Get fonts.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_fonts() {
			$fonts = [
				'Plus Jakarta Sans' => __( 'Plus Jakarta Sans', 'prebook' ),
				'Roboto' => __( 'Roboto', 'prebook' ),
				'Open Sans' => __( 'Open Sans', 'prebook' ),
				'Lato' => __( 'Lato', 'prebook' ),
				'Montserrat' => __( 'Montserrat', 'prebook' ),
				'Oswald' => __( 'Oswald', 'prebook' ),
				'Poppins' => __( 'Poppins', 'prebook' ),
				'Raleway' => __( 'Raleway', 'prebook' ),
				'Playfair Display' => __( 'Playfair Display', 'prebook' ),
				'Merriweather' => __( 'Merriweather', 'prebook' ),
			];

			return apply_filters( 'prebook_fonts', $fonts );
		}

		/**
		 * Print fonts.
		 *
		 * @since 1.0.0
		 */
		public function get_printed_fonts() {
			$css = '';

			if ( ! empty( $this->get_fonts() ) ) {
				$css .= '/* Prebook Fonts */' . PHP_EOL;

				$font_url = PREBOOK_ASSETS . 'fonts/';

				foreach ( $this->get_fonts() as $font_name => $font_family ) {

					$font_name = str_replace(' ', '', $font_name);

					// Add font family.
					$css .= "@font-face { font-family: '{$font_family}'; src: url('{$font_url}{$font_name}.ttf') format('truetype'); }" . PHP_EOL;
				}
			}

			return $css;
		}
	}
}
