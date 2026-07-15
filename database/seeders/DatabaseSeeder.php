<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Country;
use App\Models\Port;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        User::create([
            'name' => 'Admin GSC',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'User GSC',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Dictionary translation for major countries and capitals to Indonesian
        $translations = [
            // Asia
            'China'                  => ['name' => 'Tiongkok',          'capital' => 'Beijing'],
            'Japan'                  => ['name' => 'Jepang',            'capital' => 'Tokyo'],
            'South Korea'            => ['name' => 'Korea Selatan',     'capital' => 'Seoul'],
            'North Korea'            => ['name' => 'Korea Utara',       'capital' => 'Pyongyang'],
            'Singapore'              => ['name' => 'Singapura',         'capital' => 'Singapura'],
            'Malaysia'               => ['name' => 'Malaysia',          'capital' => 'Kuala Lumpur'],
            'Thailand'               => ['name' => 'Thailand',          'capital' => 'Bangkok'],
            'Philippines'            => ['name' => 'Filipina',          'capital' => 'Manila'],
            'Vietnam'                => ['name' => 'Vietnam',           'capital' => 'Hanoi'],
            'Cambodia'               => ['name' => 'Kamboja',           'capital' => 'Phnom Penh'],
            'Myanmar'                => ['name' => 'Myanmar',           'capital' => 'Naypyidaw'],
            'Laos'                   => ['name' => 'Laos',              'capital' => 'Vientiane'],
            'Brunei'                 => ['name' => 'Brunei',            'capital' => 'Bandar Seri Begawan'],
            'Timor-Leste'            => ['name' => 'Timor Leste',       'capital' => 'Dili'],
            'India'                  => ['name' => 'India',             'capital' => 'New Delhi'],
            'Pakistan'               => ['name' => 'Pakistan',          'capital' => 'Islamabad'],
            'Bangladesh'             => ['name' => 'Bangladesh',        'capital' => 'Dhaka'],
            'Sri Lanka'              => ['name' => 'Sri Lanka',         'capital' => 'Sri Jayawardenepura Kotte'],
            'Nepal'                  => ['name' => 'Nepal',             'capital' => 'Kathmandu'],
            'Bhutan'                 => ['name' => 'Bhutan',            'capital' => 'Thimphu'],
            'Maldives'               => ['name' => 'Maladewa',          'capital' => 'Malé'],
            'Afghanistan'            => ['name' => 'Afganistan',        'capital' => 'Kabul'],
            'Saudi Arabia'           => ['name' => 'Arab Saudi',        'capital' => 'Riyadh'],
            'United Arab Emirates'   => ['name' => 'Uni Emirat Arab',   'capital' => 'Abu Dhabi'],
            'Qatar'                  => ['name' => 'Qatar',             'capital' => 'Doha'],
            'Kuwait'                 => ['name' => 'Kuwait',            'capital' => 'Kuwait City'],
            'Bahrain'                => ['name' => 'Bahrain',           'capital' => 'Manama'],
            'Oman'                   => ['name' => 'Oman',              'capital' => 'Muscat'],
            'Yemen'                  => ['name' => 'Yaman',             'capital' => 'Sana\'a'],
            'Iraq'                   => ['name' => 'Irak',              'capital' => 'Baghdad'],
            'Iran'                   => ['name' => 'Iran',              'capital' => 'Teheran'],
            'Syria'                  => ['name' => 'Suriah',            'capital' => 'Damaskus'],
            'Lebanon'                => ['name' => 'Lebanon',           'capital' => 'Beirut'],
            'Jordan'                 => ['name' => 'Yordania',          'capital' => 'Amman'],
            'Israel'                 => ['name' => 'Israel',            'capital' => 'Yerusalem'],
            'Palestine'              => ['name' => 'Palestina',         'capital' => 'Ramallah'],
            'Turkey'                 => ['name' => 'Turki',             'capital' => 'Ankara'],
            'Cyprus'                 => ['name' => 'Siprus',            'capital' => 'Nikosia'],
            'Georgia'                => ['name' => 'Georgia',           'capital' => 'Tbilisi'],
            'Armenia'                => ['name' => 'Armenia',           'capital' => 'Yerevan'],
            'Azerbaijan'             => ['name' => 'Azerbaijan',        'capital' => 'Baku'],
            'Kazakhstan'             => ['name' => 'Kazakhstan',        'capital' => 'Astana'],
            'Uzbekistan'             => ['name' => 'Uzbekistan',        'capital' => 'Tashkent'],
            'Turkmenistan'           => ['name' => 'Turkmenistan',      'capital' => 'Ashgabat'],
            'Kyrgyzstan'             => ['name' => 'Kirgizstan',        'capital' => 'Bishkek'],
            'Tajikistan'             => ['name' => 'Tajikistan',        'capital' => 'Dushanbe'],
            'Mongolia'               => ['name' => 'Mongolia',          'capital' => 'Ulaanbaatar'],
            // Europe
            'Germany'                => ['name' => 'Jerman',            'capital' => 'Berlin'],
            'United Kingdom'         => ['name' => 'Inggris',           'capital' => 'London'],
            'France'                 => ['name' => 'Prancis',           'capital' => 'Paris'],
            'Italy'                  => ['name' => 'Italia',            'capital' => 'Roma'],
            'Spain'                  => ['name' => 'Spanyol',           'capital' => 'Madrid'],
            'Netherlands'            => ['name' => 'Belanda',           'capital' => 'Amsterdam'],
            'Belgium'                => ['name' => 'Belgia',            'capital' => 'Brussel'],
            'Switzerland'            => ['name' => 'Swiss',             'capital' => 'Bern'],
            'Austria'                => ['name' => 'Austria',           'capital' => 'Wina'],
            'Sweden'                 => ['name' => 'Swedia',            'capital' => 'Stockholm'],
            'Norway'                 => ['name' => 'Norwegia',          'capital' => 'Oslo'],
            'Denmark'                => ['name' => 'Denmark',           'capital' => 'Kopenhagen'],
            'Finland'                => ['name' => 'Finlandia',         'capital' => 'Helsinki'],
            'Poland'                 => ['name' => 'Polandia',          'capital' => 'Warsawa'],
            'Czech Republic'         => ['name' => 'Republik Ceko',     'capital' => 'Praha'],
            'Czechia'                => ['name' => 'Republik Ceko',     'capital' => 'Praha'],
            'Hungary'                => ['name' => 'Hungaria',          'capital' => 'Budapest'],
            'Romania'                => ['name' => 'Rumania',           'capital' => 'Bukares'],
            'Bulgaria'               => ['name' => 'Bulgaria',          'capital' => 'Sofia'],
            'Greece'                 => ['name' => 'Yunani',            'capital' => 'Atena'],
            'Portugal'               => ['name' => 'Portugal',          'capital' => 'Lisbon'],
            'Ukraine'                => ['name' => 'Ukraina',           'capital' => 'Kyiv'],
            'Russia'                 => ['name' => 'Rusia',             'capital' => 'Moskow'],
            'Serbia'                 => ['name' => 'Serbia',            'capital' => 'Beograd'],
            'Croatia'                => ['name' => 'Kroasia',           'capital' => 'Zagreb'],
            'Slovakia'               => ['name' => 'Slovakia',          'capital' => 'Bratislava'],
            'Slovenia'               => ['name' => 'Slovenia',          'capital' => 'Ljubljana'],
            'Bosnia and Herzegovina' => ['name' => 'Bosnia Herzegovina','capital' => 'Sarajevo'],
            'North Macedonia'        => ['name' => 'Makedonia Utara',   'capital' => 'Skopje'],
            'Albania'                => ['name' => 'Albania',           'capital' => 'Tirana'],
            'Moldova'                => ['name' => 'Moldova',           'capital' => 'Chisinau'],
            'Belarus'                => ['name' => 'Belarus',           'capital' => 'Minsk'],
            'Estonia'                => ['name' => 'Estonia',           'capital' => 'Tallinn'],
            'Latvia'                 => ['name' => 'Latvia',            'capital' => 'Riga'],
            'Lithuania'              => ['name' => 'Lituania',          'capital' => 'Vilnius'],
            'Luxembourg'             => ['name' => 'Luksemburg',        'capital' => 'Kota Luksemburg'],
            'Iceland'                => ['name' => 'Islandia',          'capital' => 'Reykjavik'],
            'Ireland'                => ['name' => 'Irlandia',          'capital' => 'Dublin'],
            'Malta'                  => ['name' => 'Malta',             'capital' => 'Valletta'],
            'Monaco'                 => ['name' => 'Monako',            'capital' => 'Monako'],
            'Liechtenstein'          => ['name' => 'Liechtenstein',     'capital' => 'Vaduz'],
            'Andorra'                => ['name' => 'Andorra',           'capital' => 'Andorra la Vella'],
            'San Marino'             => ['name' => 'San Marino',        'capital' => 'San Marino'],
            'Vatican City'           => ['name' => 'Vatikan',           'capital' => 'Vatikan'],
            'Kosovo'                 => ['name' => 'Kosovo',            'capital' => 'Pristina'],
            'Montenegro'             => ['name' => 'Montenegro',        'capital' => 'Podgorica'],
            // Americas
            'United States'          => ['name' => 'Amerika Serikat',   'capital' => 'Washington D.C.'],
            'Canada'                 => ['name' => 'Kanada',            'capital' => 'Ottawa'],
            'Mexico'                 => ['name' => 'Meksiko',           'capital' => 'Meksiko City'],
            'Brazil'                 => ['name' => 'Brasil',            'capital' => 'Brasilia'],
            'Argentina'              => ['name' => 'Argentina',         'capital' => 'Buenos Aires'],
            'Colombia'               => ['name' => 'Kolombia',          'capital' => 'Bogota'],
            'Chile'                  => ['name' => 'Chili',             'capital' => 'Santiago'],
            'Peru'                   => ['name' => 'Peru',              'capital' => 'Lima'],
            'Venezuela'              => ['name' => 'Venezuela',         'capital' => 'Caracas'],
            'Ecuador'                => ['name' => 'Ekuador',           'capital' => 'Quito'],
            'Bolivia'                => ['name' => 'Bolivia',           'capital' => 'Sucre'],
            'Paraguay'               => ['name' => 'Paraguay',          'capital' => 'Asuncion'],
            'Uruguay'                => ['name' => 'Uruguay',           'capital' => 'Montevideo'],
            'Cuba'                   => ['name' => 'Kuba',              'capital' => 'Havana'],
            'Dominican Republic'     => ['name' => 'Republik Dominika', 'capital' => 'Santo Domingo'],
            'Haiti'                  => ['name' => 'Haiti',             'capital' => 'Port-au-Prince'],
            'Guatemala'              => ['name' => 'Guatemala',         'capital' => 'Guatemala City'],
            'Honduras'               => ['name' => 'Honduras',          'capital' => 'Tegucigalpa'],
            'El Salvador'            => ['name' => 'El Salvador',       'capital' => 'San Salvador'],
            'Nicaragua'              => ['name' => 'Nikaragua',         'capital' => 'Managua'],
            'Costa Rica'             => ['name' => 'Kosta Rika',        'capital' => 'San José'],
            'Panama'                 => ['name' => 'Panama',            'capital' => 'Panama City'],
            'Jamaica'                => ['name' => 'Jamaika',           'capital' => 'Kingston'],
            'Trinidad and Tobago'    => ['name' => 'Trinidad dan Tobago','capital' => 'Port of Spain'],
            'Guyana'                 => ['name' => 'Guyana',            'capital' => 'Georgetown'],
            'Suriname'               => ['name' => 'Suriname',          'capital' => 'Paramaribo'],
            'Belize'                 => ['name' => 'Belize',            'capital' => 'Belmopan'],
            // Africa
            'Nigeria'                => ['name' => 'Nigeria',           'capital' => 'Abuja'],
            'Egypt'                  => ['name' => 'Mesir',             'capital' => 'Kairo'],
            'South Africa'           => ['name' => 'Afrika Selatan',    'capital' => 'Pretoria'],
            'Ethiopia'               => ['name' => 'Etiopia',           'capital' => 'Addis Ababa'],
            'Kenya'                  => ['name' => 'Kenya',             'capital' => 'Nairobi'],
            'Ghana'                  => ['name' => 'Ghana',             'capital' => 'Accra'],
            'Tanzania'               => ['name' => 'Tanzania',          'capital' => 'Dodoma'],
            'Uganda'                 => ['name' => 'Uganda',            'capital' => 'Kampala'],
            'Cameroon'               => ['name' => 'Kamerun',           'capital' => 'Yaounde'],
            'Ivory Coast'            => ['name' => 'Pantai Gading',     'capital' => 'Yamoussoukro'],
            "Côte d'Ivoire"          => ['name' => 'Pantai Gading',     'capital' => 'Yamoussoukro'],
            'Senegal'                => ['name' => 'Senegal',           'capital' => 'Dakar'],
            'Mali'                   => ['name' => 'Mali',              'capital' => 'Bamako'],
            'Burkina Faso'           => ['name' => 'Burkina Faso',      'capital' => 'Ouagadougou'],
            'Niger'                  => ['name' => 'Niger',             'capital' => 'Niamey'],
            'Chad'                   => ['name' => 'Chad',              'capital' => "N'Djamena"],
            'Sudan'                  => ['name' => 'Sudan',             'capital' => 'Khartoum'],
            'South Sudan'            => ['name' => 'Sudan Selatan',     'capital' => 'Juba'],
            'Somalia'                => ['name' => 'Somalia',           'capital' => 'Mogadishu'],
            'Libya'                  => ['name' => 'Libya',             'capital' => 'Tripoli'],
            'Algeria'                => ['name' => 'Aljazair',          'capital' => 'Algiers'],
            'Morocco'                => ['name' => 'Maroko',            'capital' => 'Rabat'],
            'Tunisia'                => ['name' => 'Tunisia',           'capital' => 'Tunis'],
            'Mozambique'             => ['name' => 'Mozambik',          'capital' => 'Maputo'],
            'Madagascar'             => ['name' => 'Madagaskar',        'capital' => 'Antananarivo'],
            'Angola'                 => ['name' => 'Angola',            'capital' => 'Luanda'],
            'Zambia'                 => ['name' => 'Zambia',            'capital' => 'Lusaka'],
            'Zimbabwe'               => ['name' => 'Zimbabwe',          'capital' => 'Harare'],
            'Rwanda'                 => ['name' => 'Rwanda',            'capital' => 'Kigali'],
            'Burundi'                => ['name' => 'Burundi',           'capital' => 'Gitega'],
            'Democratic Republic of the Congo' => ['name' => 'Kongo (DRC)', 'capital' => 'Kinshasa'],
            'Republic of the Congo' => ['name' => 'Republik Kongo',    'capital' => 'Brazzaville'],
            'Gabon'                  => ['name' => 'Gabon',             'capital' => 'Libreville'],
            'Benin'                  => ['name' => 'Benin',             'capital' => 'Porto-Novo'],
            'Togo'                   => ['name' => 'Togo',              'capital' => 'Lome'],
            'Guinea'                 => ['name' => 'Guinea',            'capital' => 'Conakry'],
            'Guinea-Bissau'          => ['name' => 'Guinea-Bissau',     'capital' => 'Bissau'],
            'Sierra Leone'           => ['name' => 'Sierra Leone',      'capital' => 'Freetown'],
            'Liberia'                => ['name' => 'Liberia',           'capital' => 'Monrovia'],
            'Mauritania'             => ['name' => 'Mauritania',        'capital' => 'Nouakchott'],
            'Eritrea'                => ['name' => 'Eritrea',           'capital' => 'Asmara'],
            'Djibouti'               => ['name' => 'Djibouti',          'capital' => 'Djibouti'],
            'Comoros'                => ['name' => 'Komoro',            'capital' => 'Moroni'],
            'Mauritius'              => ['name' => 'Mauritius',         'capital' => 'Port Louis'],
            'Seychelles'             => ['name' => 'Seychelles',        'capital' => 'Victoria'],
            'Cape Verde'             => ['name' => 'Tanjung Verde',     'capital' => 'Praia'],
            'Equatorial Guinea'      => ['name' => 'Guinea Khatulistiwa','capital' => 'Malabo'],
            'São Tomé and Príncipe'  => ['name' => 'Sao Tome dan Principe','capital' => 'São Tomé'],
            'Lesotho'                => ['name' => 'Lesotho',           'capital' => 'Maseru'],
            'Swaziland'              => ['name' => 'Eswatini',          'capital' => 'Mbabane'],
            'Eswatini'               => ['name' => 'Eswatini',          'capital' => 'Mbabane'],
            'Botswana'               => ['name' => 'Botswana',          'capital' => 'Gaborone'],
            'Namibia'                => ['name' => 'Namibia',           'capital' => 'Windhoek'],
            'Malawi'                 => ['name' => 'Malawi',            'capital' => 'Lilongwe'],
            'Central African Republic' => ['name' => 'Republik Afrika Tengah','capital' => 'Bangui'],
            // Oceania
            'Australia'              => ['name' => 'Australia',         'capital' => 'Canberra'],
            'New Zealand'            => ['name' => 'Selandia Baru',     'capital' => 'Wellington'],
            'Papua New Guinea'       => ['name' => 'Papua Nugini',      'capital' => 'Port Moresby'],
            'Fiji'                   => ['name' => 'Fiji',              'capital' => 'Suva'],
            'Solomon Islands'        => ['name' => 'Kepulauan Solomon', 'capital' => 'Honiara'],
            'Vanuatu'                => ['name' => 'Vanuatu',           'capital' => 'Port Vila'],
            'Samoa'                  => ['name' => 'Samoa',             'capital' => 'Apia'],
            'Tonga'                  => ['name' => 'Tonga',             'capital' => "Nuku'alofa"],
            'Kiribati'               => ['name' => 'Kiribati',          'capital' => 'South Tarawa'],
            'Micronesia'             => ['name' => 'Mikronesia',        'capital' => 'Palikir'],
            'Palau'                  => ['name' => 'Palau',             'capital' => 'Ngerulmud'],
            'Marshall Islands'       => ['name' => 'Kepulauan Marshall','capital' => 'Majuro'],
            'Nauru'                  => ['name' => 'Nauru',             'capital' => 'Yaren'],
            'Tuvalu'                 => ['name' => 'Tuvalu',            'capital' => 'Funafuti'],
        ];

        // 2. Seed Countries — hanya ~195 negara merdeka yang diakui dunia
        $countries = [];
        try {
            $response = Http::timeout(30)->get("https://raw.githubusercontent.com/mledoze/countries/master/dist/countries.json");
            if ($response->successful()) {
                $apiCountries = $response->json();
                foreach ($apiCountries as $item) {
                    $code = $item['cca2'] ?? '';
                    // Lewati entri tanpa kode negara 2 huruf
                    if (empty($code) || strlen($code) !== 2) {
                        continue;
                    }
                    // Hanya negara merdeka yang diakui secara internasional
                    if (($item['independent'] ?? false) !== true) {
                        continue;
                    }
                    // Hanya negara dengan status officially-assigned (bukan user-assigned)
                    if (($item['status'] ?? '') !== 'officially-assigned') {
                        continue;
                    }

                    $currencyCode = null;
                    $currencyName = null;
                    if (!empty($item['currencies'])) {
                        $keys = array_keys($item['currencies']);
                        $currencyCode = $keys[0];
                        $currencyName = $item['currencies'][$currencyCode]['name'] ?? null;
                    }

                    $commonName = $item['name']['common'] ?? ($item['name']['official'] ?? 'Unknown');
                    $capitalName = !empty($item['capital']) ? $item['capital'][0] : $commonName;

                    // Terjemahkan nama negara & ibukota ke Bahasa Indonesia jika tersedia
                    if (isset($translations[$commonName])) {
                        $name = $translations[$commonName]['name'];
                        $capitalName = $translations[$commonName]['capital'];
                    } else {
                        $name = $commonName;
                    }

                    $countries[] = [
                        'name'          => $name,
                        'code'          => $code,
                        'flag'          => $item['flag'] ?? '🏳️',
                        'capital'       => $capitalName,
                        'region'        => $item['region'] ?? 'Other',
                        'population'    => rand(5, 1500) * 1000000,
                        'gdp'           => rand(1, 250) * 1000000000 * rand(1, 100),
                        'inflation'     => rand(5, 150) / 10,
                        'currency_code' => $currencyCode ?? 'USD',
                        'currency_name' => $currencyName ?? 'US Dollar',
                        'latitude'      => $item['latlng'][0] ?? 0.0,
                        'longitude'     => $item['latlng'][1] ?? 0.0,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning("Gagal fetch countries JSON dari GitHub, menggunakan fallback: " . $e->getMessage());
        }

        // Fallback jika API gagal/offline — mencakup negara-negara utama dari semua kawasan
        if (empty($countries)) {
            $countries = [
                // Asia Tenggara
                ['name'=>'Indonesia','code'=>'ID','flag'=>'🇮🇩','capital'=>'Jakarta','region'=>'Asia','population'=>277534122,'gdp'=>1371171000000,'inflation'=>2.6,'currency_code'=>'IDR','currency_name'=>'Indonesian Rupiah','latitude'=>-0.7893,'longitude'=>113.9213],
                ['name'=>'Singapura','code'=>'SG','flag'=>'🇸🇬','capital'=>'Singapura','region'=>'Asia','population'=>5917600,'gdp'=>501388000000,'inflation'=>4.8,'currency_code'=>'SGD','currency_name'=>'Singapore Dollar','latitude'=>1.3521,'longitude'=>103.8198],
                ['name'=>'Malaysia','code'=>'MY','flag'=>'🇲🇾','capital'=>'Kuala Lumpur','region'=>'Asia','population'=>32600000,'gdp'=>407650000000,'inflation'=>3.5,'currency_code'=>'MYR','currency_name'=>'Malaysian Ringgit','latitude'=>4.2105,'longitude'=>101.9758],
                ['name'=>'Thailand','code'=>'TH','flag'=>'🇹🇭','capital'=>'Bangkok','region'=>'Asia','population'=>70000000,'gdp'=>496000000000,'inflation'=>2.8,'currency_code'=>'THB','currency_name'=>'Thai Baht','latitude'=>15.8700,'longitude'=>100.9925],
                ['name'=>'Filipina','code'=>'PH','flag'=>'🇵🇭','capital'=>'Manila','region'=>'Asia','population'=>114164000,'gdp'=>394000000000,'inflation'=>5.8,'currency_code'=>'PHP','currency_name'=>'Philippine Peso','latitude'=>12.8797,'longitude'=>121.7740],
                ['name'=>'Vietnam','code'=>'VN','flag'=>'🇻🇳','capital'=>'Hanoi','region'=>'Asia','population'=>97340000,'gdp'=>362000000000,'inflation'=>3.3,'currency_code'=>'VND','currency_name'=>'Vietnamese Dong','latitude'=>14.0583,'longitude'=>108.2772],
                ['name'=>'Myanmar','code'=>'MM','flag'=>'🇲🇲','capital'=>'Naypyidaw','region'=>'Asia','population'=>54000000,'gdp'=>65000000000,'inflation'=>8.2,'currency_code'=>'MMK','currency_name'=>'Burmese Kyat','latitude'=>21.9162,'longitude'=>95.9560],
                ['name'=>'Kamboja','code'=>'KH','flag'=>'🇰🇭','capital'=>'Phnom Penh','region'=>'Asia','population'=>17000000,'gdp'=>27000000000,'inflation'=>3.0,'currency_code'=>'KHR','currency_name'=>'Cambodian Riel','latitude'=>12.5657,'longitude'=>104.9910],
                ['name'=>'Laos','code'=>'LA','flag'=>'🇱🇦','capital'=>'Vientiane','region'=>'Asia','population'=>7500000,'gdp'=>18900000000,'inflation'=>6.0,'currency_code'=>'LAK','currency_name'=>'Lao Kip','latitude'=>19.8563,'longitude'=>102.4955],
                ['name'=>'Brunei','code'=>'BN','flag'=>'🇧🇳','capital'=>'Bandar Seri Begawan','region'=>'Asia','population'=>450000,'gdp'=>14000000000,'inflation'=>1.5,'currency_code'=>'BND','currency_name'=>'Brunei Dollar','latitude'=>4.5353,'longitude'=>114.7277],
                ['name'=>'Timor Leste','code'=>'TL','flag'=>'🇹🇱','capital'=>'Dili','region'=>'Asia','population'=>1340000,'gdp'=>2200000000,'inflation'=>4.5,'currency_code'=>'USD','currency_name'=>'US Dollar','latitude'=>-8.8742,'longitude'=>125.7275],
                // Asia Timur
                ['name'=>'Tiongkok','code'=>'CN','flag'=>'🇨🇳','capital'=>'Beijing','region'=>'Asia','population'=>1412175000,'gdp'=>17794780000000,'inflation'=>0.2,'currency_code'=>'CNY','currency_name'=>'Chinese Yuan','latitude'=>35.8617,'longitude'=>104.1954],
                ['name'=>'Jepang','code'=>'JP','flag'=>'🇯🇵','capital'=>'Tokyo','region'=>'Asia','population'=>125124000,'gdp'=>4210600000000,'inflation'=>2.5,'currency_code'=>'JPY','currency_name'=>'Japanese Yen','latitude'=>36.2048,'longitude'=>138.2529],
                ['name'=>'Korea Selatan','code'=>'KR','flag'=>'🇰🇷','capital'=>'Seoul','region'=>'Asia','population'=>51740000,'gdp'=>1673000000000,'inflation'=>3.2,'currency_code'=>'KRW','currency_name'=>'South Korean Won','latitude'=>35.9078,'longitude'=>127.7669],
                ['name'=>'Korea Utara','code'=>'KP','flag'=>'🇰🇵','capital'=>'Pyongyang','region'=>'Asia','population'=>26000000,'gdp'=>18000000000,'inflation'=>5.0,'currency_code'=>'KPW','currency_name'=>'North Korean Won','latitude'=>40.3399,'longitude'=>127.5101],
                ['name'=>'Mongolia','code'=>'MN','flag'=>'🇲🇳','capital'=>'Ulaanbaatar','region'=>'Asia','population'=>3400000,'gdp'=>16900000000,'inflation'=>7.2,'currency_code'=>'MNT','currency_name'=>'Mongolian Tögrög','latitude'=>46.8625,'longitude'=>103.8467],
                // Asia Selatan
                ['name'=>'India','code'=>'IN','flag'=>'🇮🇳','capital'=>'New Delhi','region'=>'Asia','population'=>1408000000,'gdp'=>3390000000000,'inflation'=>5.5,'currency_code'=>'INR','currency_name'=>'Indian Rupee','latitude'=>20.5937,'longitude'=>78.9629],
                ['name'=>'Pakistan','code'=>'PK','flag'=>'🇵🇰','capital'=>'Islamabad','region'=>'Asia','population'=>225000000,'gdp'=>341000000000,'inflation'=>29.0,'currency_code'=>'PKR','currency_name'=>'Pakistani Rupee','latitude'=>30.3753,'longitude'=>69.3451],
                ['name'=>'Bangladesh','code'=>'BD','flag'=>'🇧🇩','capital'=>'Dhaka','region'=>'Asia','population'=>170000000,'gdp'=>460000000000,'inflation'=>9.5,'currency_code'=>'BDT','currency_name'=>'Bangladeshi Taka','latitude'=>23.6850,'longitude'=>90.3563],
                ['name'=>'Sri Lanka','code'=>'LK','flag'=>'🇱🇰','capital'=>'Sri Jayawardenepura Kotte','region'=>'Asia','population'=>22000000,'gdp'=>75000000000,'inflation'=>12.0,'currency_code'=>'LKR','currency_name'=>'Sri Lankan Rupee','latitude'=>7.8731,'longitude'=>80.7718],
                ['name'=>'Nepal','code'=>'NP','flag'=>'🇳🇵','capital'=>'Kathmandu','region'=>'Asia','population'=>30000000,'gdp'=>40000000000,'inflation'=>8.0,'currency_code'=>'NPR','currency_name'=>'Nepalese Rupee','latitude'=>28.3949,'longitude'=>84.1240],
                ['name'=>'Bhutan','code'=>'BT','flag'=>'🇧🇹','capital'=>'Thimphu','region'=>'Asia','population'=>760000,'gdp'=>2700000000,'inflation'=>4.2,'currency_code'=>'BTN','currency_name'=>'Bhutanese Ngultrum','latitude'=>27.5142,'longitude'=>90.4336],
                ['name'=>'Maladewa','code'=>'MV','flag'=>'🇲🇻','capital'=>'Malé','region'=>'Asia','population'=>521000,'gdp'=>5800000000,'inflation'=>3.5,'currency_code'=>'MVR','currency_name'=>'Maldivian Rufiyaa','latitude'=>3.2028,'longitude'=>73.2207],
                // Asia Tengah
                ['name'=>'Kazakhstan','code'=>'KZ','flag'=>'🇰🇿','capital'=>'Astana','region'=>'Asia','population'=>19000000,'gdp'=>220000000000,'inflation'=>14.7,'currency_code'=>'KZT','currency_name'=>'Kazakhstani Tenge','latitude'=>48.0196,'longitude'=>66.9237],
                ['name'=>'Uzbekistan','code'=>'UZ','flag'=>'🇺🇿','capital'=>'Tashkent','region'=>'Asia','population'=>35000000,'gdp'=>80000000000,'inflation'=>12.0,'currency_code'=>'UZS','currency_name'=>'Uzbekistani Som','latitude'=>41.3775,'longitude'=>64.5853],
                ['name'=>'Turkmenistan','code'=>'TM','flag'=>'🇹🇲','capital'=>'Ashgabat','region'=>'Asia','population'=>6000000,'gdp'=>45000000000,'inflation'=>8.0,'currency_code'=>'TMT','currency_name'=>'Turkmenistani Manat','latitude'=>38.9697,'longitude'=>59.5563],
                ['name'=>'Kirgizstan','code'=>'KG','flag'=>'🇰🇬','capital'=>'Bishkek','region'=>'Asia','population'=>6800000,'gdp'=>11000000000,'inflation'=>13.9,'currency_code'=>'KGS','currency_name'=>'Kyrgystani Som','latitude'=>41.2044,'longitude'=>74.7661],
                ['name'=>'Tajikistan','code'=>'TJ','flag'=>'🇹🇯','capital'=>'Dushanbe','region'=>'Asia','population'=>10000000,'gdp'=>10000000000,'inflation'=>4.7,'currency_code'=>'TJS','currency_name'=>'Tajikistani Somoni','latitude'=>38.8610,'longitude'=>71.2761],
                ['name'=>'Afganistan','code'=>'AF','flag'=>'🇦🇫','capital'=>'Kabul','region'=>'Asia','population'=>40000000,'gdp'=>14800000000,'inflation'=>18.0,'currency_code'=>'AFN','currency_name'=>'Afghan Afghani','latitude'=>33.9391,'longitude'=>67.7100],
                // Asia Barat / Timur Tengah
                ['name'=>'Arab Saudi','code'=>'SA','flag'=>'🇸🇦','capital'=>'Riyadh','region'=>'Asia','population'=>36400000,'gdp'=>1108000000000,'inflation'=>1.6,'currency_code'=>'SAR','currency_name'=>'Saudi Riyal','latitude'=>23.8859,'longitude'=>45.0792],
                ['name'=>'Uni Emirat Arab','code'=>'AE','flag'=>'🇦🇪','capital'=>'Abu Dhabi','region'=>'Asia','population'=>10000000,'gdp'=>500000000000,'inflation'=>4.8,'currency_code'=>'AED','currency_name'=>'UAE Dirham','latitude'=>23.4241,'longitude'=>53.8478],
                ['name'=>'Qatar','code'=>'QA','flag'=>'🇶🇦','capital'=>'Doha','region'=>'Asia','population'=>2900000,'gdp'=>220000000000,'inflation'=>3.0,'currency_code'=>'QAR','currency_name'=>'Qatari Riyal','latitude'=>25.3548,'longitude'=>51.1839],
                ['name'=>'Kuwait','code'=>'KW','flag'=>'🇰🇼','capital'=>'Kuwait City','region'=>'Asia','population'=>4300000,'gdp'=>163000000000,'inflation'=>3.5,'currency_code'=>'KWD','currency_name'=>'Kuwaiti Dinar','latitude'=>29.3117,'longitude'=>47.4818],
                ['name'=>'Bahrain','code'=>'BH','flag'=>'🇧🇭','capital'=>'Manama','region'=>'Asia','population'=>1700000,'gdp'=>38000000000,'inflation'=>3.3,'currency_code'=>'BHD','currency_name'=>'Bahraini Dinar','latitude'=>26.0275,'longitude'=>50.5500],
                ['name'=>'Oman','code'=>'OM','flag'=>'🇴🇲','capital'=>'Muscat','region'=>'Asia','population'=>4700000,'gdp'=>102000000000,'inflation'=>1.5,'currency_code'=>'OMR','currency_name'=>'Omani Rial','latitude'=>21.5125,'longitude'=>55.9233],
                ['name'=>'Yaman','code'=>'YE','flag'=>'🇾🇪','capital'=>'Sanaa','region'=>'Asia','population'=>33000000,'gdp'=>18000000000,'inflation'=>40.0,'currency_code'=>'YER','currency_name'=>'Yemeni Rial','latitude'=>15.5527,'longitude'=>48.5164],
                ['name'=>'Irak','code'=>'IQ','flag'=>'🇮🇶','capital'=>'Baghdad','region'=>'Asia','population'=>42000000,'gdp'=>207000000000,'inflation'=>4.5,'currency_code'=>'IQD','currency_name'=>'Iraqi Dinar','latitude'=>33.2232,'longitude'=>43.6793],
                ['name'=>'Iran','code'=>'IR','flag'=>'🇮🇷','capital'=>'Teheran','region'=>'Asia','population'=>87000000,'gdp'=>368000000000,'inflation'=>40.3,'currency_code'=>'IRR','currency_name'=>'Iranian Rial','latitude'=>32.4279,'longitude'=>53.6880],
                ['name'=>'Suriah','code'=>'SY','flag'=>'🇸🇾','capital'=>'Damaskus','region'=>'Asia','population'=>21000000,'gdp'=>11000000000,'inflation'=>50.0,'currency_code'=>'SYP','currency_name'=>'Syrian Pound','latitude'=>34.8021,'longitude'=>38.9968],
                ['name'=>'Lebanon','code'=>'LB','flag'=>'🇱🇧','capital'=>'Beirut','region'=>'Asia','population'=>5500000,'gdp'=>19000000000,'inflation'=>90.0,'currency_code'=>'LBP','currency_name'=>'Lebanese Pound','latitude'=>33.8547,'longitude'=>35.8623],
                ['name'=>'Yordania','code'=>'JO','flag'=>'🇯🇴','capital'=>'Amman','region'=>'Asia','population'=>10000000,'gdp'=>50000000000,'inflation'=>3.7,'currency_code'=>'JOD','currency_name'=>'Jordanian Dinar','latitude'=>30.5852,'longitude'=>36.2384],
                ['name'=>'Israel','code'=>'IL','flag'=>'🇮🇱','capital'=>'Yerusalem','region'=>'Asia','population'=>9700000,'gdp'=>522000000000,'inflation'=>4.2,'currency_code'=>'ILS','currency_name'=>'Israeli New Shekel','latitude'=>31.0461,'longitude'=>34.8516],
                ['name'=>'Turki','code'=>'TR','flag'=>'🇹🇷','capital'=>'Ankara','region'=>'Asia','population'=>85300000,'gdp'=>1029000000000,'inflation'=>64.8,'currency_code'=>'TRY','currency_name'=>'Turkish Lira','latitude'=>38.9637,'longitude'=>35.2433],
                ['name'=>'Siprus','code'=>'CY','flag'=>'🇨🇾','capital'=>'Nikosia','region'=>'Europe','population'=>1200000,'gdp'=>28000000000,'inflation'=>3.5,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>35.1264,'longitude'=>33.4299],
                ['name'=>'Georgia','code'=>'GE','flag'=>'🇬🇪','capital'=>'Tbilisi','region'=>'Asia','population'=>4000000,'gdp'=>28000000000,'inflation'=>9.8,'currency_code'=>'GEL','currency_name'=>'Georgian Lari','latitude'=>42.3154,'longitude'=>43.3569],
                ['name'=>'Armenia','code'=>'AM','flag'=>'🇦🇲','capital'=>'Yerevan','region'=>'Asia','population'=>3000000,'gdp'=>20000000000,'inflation'=>8.7,'currency_code'=>'AMD','currency_name'=>'Armenian Dram','latitude'=>40.0691,'longitude'=>45.0382],
                ['name'=>'Azerbaijan','code'=>'AZ','flag'=>'🇦🇿','capital'=>'Baku','region'=>'Asia','population'=>10200000,'gdp'=>78000000000,'inflation'=>14.0,'currency_code'=>'AZN','currency_name'=>'Azerbaijani Manat','latitude'=>40.1431,'longitude'=>47.5769],
                // Eropa Barat
                ['name'=>'Jerman','code'=>'DE','flag'=>'🇩🇪','capital'=>'Berlin','region'=>'Europe','population'=>84358000,'gdp'=>4456081000000,'inflation'=>5.9,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>51.1657,'longitude'=>10.4515],
                ['name'=>'Inggris','code'=>'GB','flag'=>'🇬🇧','capital'=>'London','region'=>'Europe','population'=>67081000,'gdp'=>3131000000000,'inflation'=>4.0,'currency_code'=>'GBP','currency_name'=>'British Pound Sterling','latitude'=>55.3781,'longitude'=>-3.4360],
                ['name'=>'Prancis','code'=>'FR','flag'=>'🇫🇷','capital'=>'Paris','region'=>'Europe','population'=>67970000,'gdp'=>2782000000000,'inflation'=>3.7,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>46.2276,'longitude'=>2.2137],
                ['name'=>'Italia','code'=>'IT','flag'=>'🇮🇹','capital'=>'Roma','region'=>'Europe','population'=>60000000,'gdp'=>2100000000000,'inflation'=>5.7,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>41.8719,'longitude'=>12.5674],
                ['name'=>'Spanyol','code'=>'ES','flag'=>'🇪🇸','capital'=>'Madrid','region'=>'Europe','population'=>47400000,'gdp'=>1580000000000,'inflation'=>3.5,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>40.4637,'longitude'=>-3.7492],
                ['name'=>'Belanda','code'=>'NL','flag'=>'🇳🇱','capital'=>'Amsterdam','region'=>'Europe','population'=>17700100,'gdp'=>1090000000000,'inflation'=>3.8,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>52.1326,'longitude'=>5.2913],
                ['name'=>'Belgia','code'=>'BE','flag'=>'🇧🇪','capital'=>'Brussel','region'=>'Europe','population'=>11600000,'gdp'=>625000000000,'inflation'=>4.1,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>50.5039,'longitude'=>4.4699],
                ['name'=>'Swiss','code'=>'CH','flag'=>'🇨🇭','capital'=>'Bern','region'=>'Europe','population'=>8815000,'gdp'=>807000000000,'inflation'=>2.1,'currency_code'=>'CHF','currency_name'=>'Swiss Franc','latitude'=>46.8182,'longitude'=>8.2275],
                ['name'=>'Austria','code'=>'AT','flag'=>'🇦🇹','capital'=>'Wina','region'=>'Europe','population'=>9100000,'gdp'=>470000000000,'inflation'=>8.6,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>47.5162,'longitude'=>14.5501],
                ['name'=>'Swedia','code'=>'SE','flag'=>'🇸🇪','capital'=>'Stockholm','region'=>'Europe','population'=>10500000,'gdp'=>540000000000,'inflation'=>8.5,'currency_code'=>'SEK','currency_name'=>'Swedish Krona','latitude'=>60.1282,'longitude'=>18.6435],
                ['name'=>'Norwegia','code'=>'NO','flag'=>'🇳🇴','capital'=>'Oslo','region'=>'Europe','population'=>5400000,'gdp'=>579000000000,'inflation'=>5.5,'currency_code'=>'NOK','currency_name'=>'Norwegian Krone','latitude'=>60.4720,'longitude'=>8.4689],
                ['name'=>'Denmark','code'=>'DK','flag'=>'🇩🇰','capital'=>'Kopenhagen','region'=>'Europe','population'=>5900000,'gdp'=>404000000000,'inflation'=>7.7,'currency_code'=>'DKK','currency_name'=>'Danish Krone','latitude'=>56.2639,'longitude'=>9.5018],
                ['name'=>'Finlandia','code'=>'FI','flag'=>'🇫🇮','capital'=>'Helsinki','region'=>'Europe','population'=>5540000,'gdp'=>276000000000,'inflation'=>8.3,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>61.9241,'longitude'=>25.7482],
                ['name'=>'Portugal','code'=>'PT','flag'=>'🇵🇹','capital'=>'Lisbon','region'=>'Europe','population'=>10300000,'gdp'=>247000000000,'inflation'=>7.8,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>39.3999,'longitude'=>-8.2245],
                ['name'=>'Irlandia','code'=>'IE','flag'=>'🇮🇪','capital'=>'Dublin','region'=>'Europe','population'=>5100000,'gdp'=>533000000000,'inflation'=>6.2,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>53.1424,'longitude'=>-7.6921],
                ['name'=>'Yunani','code'=>'GR','flag'=>'🇬🇷','capital'=>'Atena','region'=>'Europe','population'=>10700000,'gdp'=>219000000000,'inflation'=>9.0,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>39.0742,'longitude'=>21.8243],
                ['name'=>'Luksemburg','code'=>'LU','flag'=>'🇱🇺','capital'=>'Kota Luksemburg','region'=>'Europe','population'=>645000,'gdp'=>82000000000,'inflation'=>6.0,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>49.8153,'longitude'=>6.1296],
                ['name'=>'Islandia','code'=>'IS','flag'=>'🇮🇸','capital'=>'Reykjavik','region'=>'Europe','population'=>370000,'gdp'=>28000000000,'inflation'=>7.8,'currency_code'=>'ISK','currency_name'=>'Icelandic Króna','latitude'=>64.9631,'longitude'=>-19.0208],
                ['name'=>'Malta','code'=>'MT','flag'=>'🇲🇹','capital'=>'Valletta','region'=>'Europe','population'=>520000,'gdp'=>16000000000,'inflation'=>6.0,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>35.9375,'longitude'=>14.3754],
                ['name'=>'Monako','code'=>'MC','flag'=>'🇲🇨','capital'=>'Monako','region'=>'Europe','population'=>38000,'gdp'=>7200000000,'inflation'=>3.0,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>43.7384,'longitude'=>7.4246],
                ['name'=>'Liechtenstein','code'=>'LI','flag'=>'🇱🇮','capital'=>'Vaduz','region'=>'Europe','population'=>38000,'gdp'=>6300000000,'inflation'=>3.2,'currency_code'=>'CHF','currency_name'=>'Swiss Franc','latitude'=>47.1660,'longitude'=>9.5554],
                ['name'=>'Andorra','code'=>'AD','flag'=>'🇦🇩','capital'=>'Andorra la Vella','region'=>'Europe','population'=>77000,'gdp'=>3300000000,'inflation'=>3.1,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>42.5063,'longitude'=>1.5218],
                ['name'=>'San Marino','code'=>'SM','flag'=>'🇸🇲','capital'=>'San Marino','region'=>'Europe','population'=>34000,'gdp'=>1870000000,'inflation'=>3.0,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>43.9424,'longitude'=>12.4578],
                ['name'=>'Vatikan','code'=>'VA','flag'=>'🇻🇦','capital'=>'Vatikan','region'=>'Europe','population'=>800,'gdp'=>10000000,'inflation'=>2.0,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>41.9029,'longitude'=>12.4534],
                // Eropa Timur
                ['name'=>'Rusia','code'=>'RU','flag'=>'🇷🇺','capital'=>'Moskow','region'=>'Europe','population'=>144238000,'gdp'=>2021421000000,'inflation'=>7.4,'currency_code'=>'RUB','currency_name'=>'Russian Ruble','latitude'=>61.5240,'longitude'=>105.3188],
                ['name'=>'Ukraina','code'=>'UA','flag'=>'🇺🇦','capital'=>'Kyiv','region'=>'Europe','population'=>44000000,'gdp'=>160000000000,'inflation'=>15.0,'currency_code'=>'UAH','currency_name'=>'Ukrainian Hryvnia','latitude'=>48.3794,'longitude'=>31.1656],
                ['name'=>'Polandia','code'=>'PL','flag'=>'🇵🇱','capital'=>'Warsawa','region'=>'Europe','population'=>38000000,'gdp'=>688000000000,'inflation'=>11.5,'currency_code'=>'PLN','currency_name'=>'Polish Złoty','latitude'=>51.9194,'longitude'=>19.1451],
                ['name'=>'Republik Ceko','code'=>'CZ','flag'=>'🇨🇿','capital'=>'Praha','region'=>'Europe','population'=>10900000,'gdp'=>296000000000,'inflation'=>14.8,'currency_code'=>'CZK','currency_name'=>'Czech Koruna','latitude'=>49.8175,'longitude'=>15.4730],
                ['name'=>'Hungaria','code'=>'HU','flag'=>'🇭🇺','capital'=>'Budapest','region'=>'Europe','population'=>9700000,'gdp'=>180000000000,'inflation'=>25.0,'currency_code'=>'HUF','currency_name'=>'Hungarian Forint','latitude'=>47.1625,'longitude'=>19.5033],
                ['name'=>'Rumania','code'=>'RO','flag'=>'🇷🇴','capital'=>'Bukares','region'=>'Europe','population'=>19000000,'gdp'=>301000000000,'inflation'=>13.8,'currency_code'=>'RON','currency_name'=>'Romanian Leu','latitude'=>45.9432,'longitude'=>24.9668],
                ['name'=>'Bulgaria','code'=>'BG','flag'=>'🇧🇬','capital'=>'Sofia','region'=>'Europe','population'=>6500000,'gdp'=>90000000000,'inflation'=>14.3,'currency_code'=>'BGN','currency_name'=>'Bulgarian Lev','latitude'=>42.7339,'longitude'=>25.4858],
                ['name'=>'Serbia','code'=>'RS','flag'=>'🇷🇸','capital'=>'Beograd','region'=>'Europe','population'=>7000000,'gdp'=>63000000000,'inflation'=>14.0,'currency_code'=>'RSD','currency_name'=>'Serbian Dinar','latitude'=>44.0165,'longitude'=>21.0059],
                ['name'=>'Kroasia','code'=>'HR','flag'=>'🇭🇷','capital'=>'Zagreb','region'=>'Europe','population'=>3900000,'gdp'=>66000000000,'inflation'=>10.8,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>45.1000,'longitude'=>15.2000],
                ['name'=>'Slovakia','code'=>'SK','flag'=>'🇸🇰','capital'=>'Bratislava','region'=>'Europe','population'=>5500000,'gdp'=>115000000000,'inflation'=>14.0,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>48.6690,'longitude'=>19.6990],
                ['name'=>'Slovenia','code'=>'SI','flag'=>'🇸🇮','capital'=>'Ljubljana','region'=>'Europe','population'=>2100000,'gdp'=>62000000000,'inflation'=>10.3,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>46.1512,'longitude'=>14.9955],
                ['name'=>'Bosnia Herzegovina','code'=>'BA','flag'=>'🇧🇦','capital'=>'Sarajevo','region'=>'Europe','population'=>3300000,'gdp'=>23000000000,'inflation'=>6.8,'currency_code'=>'BAM','currency_name'=>'Bosnia-Herzegovina Convertible Mark','latitude'=>43.9159,'longitude'=>17.6791],
                ['name'=>'Makedonia Utara','code'=>'MK','flag'=>'🇲🇰','capital'=>'Skopje','region'=>'Europe','population'=>2100000,'gdp'=>13000000000,'inflation'=>14.2,'currency_code'=>'MKD','currency_name'=>'Macedonian Denar','latitude'=>41.6086,'longitude'=>21.7453],
                ['name'=>'Albania','code'=>'AL','flag'=>'🇦🇱','capital'=>'Tirana','region'=>'Europe','population'=>2800000,'gdp'=>20000000000,'inflation'=>8.3,'currency_code'=>'ALL','currency_name'=>'Albanian Lek','latitude'=>41.1533,'longitude'=>20.1683],
                ['name'=>'Montenegro','code'=>'ME','flag'=>'🇲🇪','capital'=>'Podgorica','region'=>'Europe','population'=>620000,'gdp'=>7000000000,'inflation'=>13.0,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>42.7087,'longitude'=>19.3744],
                ['name'=>'Kosovo','code'=>'XK','flag'=>'🇽🇰','capital'=>'Pristina','region'=>'Europe','population'=>1800000,'gdp'=>9500000000,'inflation'=>11.6,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>42.6026,'longitude'=>20.9030],
                ['name'=>'Moldova','code'=>'MD','flag'=>'🇲🇩','capital'=>'Chisinau','region'=>'Europe','population'=>2600000,'gdp'=>15000000000,'inflation'=>29.1,'currency_code'=>'MDL','currency_name'=>'Moldovan Leu','latitude'=>47.4116,'longitude'=>28.3699],
                ['name'=>'Belarus','code'=>'BY','flag'=>'🇧🇾','capital'=>'Minsk','region'=>'Europe','population'=>9400000,'gdp'=>66000000000,'inflation'=>12.8,'currency_code'=>'BYN','currency_name'=>'Belarusian Ruble','latitude'=>53.7098,'longitude'=>27.9534],
                ['name'=>'Estonia','code'=>'EE','flag'=>'🇪🇪','capital'=>'Tallinn','region'=>'Europe','population'=>1330000,'gdp'=>38000000000,'inflation'=>19.4,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>58.5953,'longitude'=>25.0136],
                ['name'=>'Latvia','code'=>'LV','flag'=>'🇱🇻','capital'=>'Riga','region'=>'Europe','population'=>1850000,'gdp'=>40000000000,'inflation'=>20.7,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>56.8796,'longitude'=>24.6032],
                ['name'=>'Lituania','code'=>'LT','flag'=>'🇱🇹','capital'=>'Vilnius','region'=>'Europe','population'=>2800000,'gdp'=>68000000000,'inflation'=>18.9,'currency_code'=>'EUR','currency_name'=>'Euro','latitude'=>55.1694,'longitude'=>23.8813],
                // Amerika Utara
                ['name'=>'Amerika Serikat','code'=>'US','flag'=>'🇺🇸','capital'=>'Washington D.C.','region'=>'Americas','population'=>333287557,'gdp'=>27360930000000,'inflation'=>3.4,'currency_code'=>'USD','currency_name'=>'US Dollar','latitude'=>37.0902,'longitude'=>-95.7129],
                ['name'=>'Kanada','code'=>'CA','flag'=>'🇨🇦','capital'=>'Ottawa','region'=>'Americas','population'=>38930000,'gdp'=>2140000000000,'inflation'=>3.9,'currency_code'=>'CAD','currency_name'=>'Canadian Dollar','latitude'=>56.1304,'longitude'=>-106.3468],
                ['name'=>'Meksiko','code'=>'MX','flag'=>'🇲🇽','capital'=>'Meksiko City','region'=>'Americas','population'=>130000000,'gdp'=>1323000000000,'inflation'=>5.1,'currency_code'=>'MXN','currency_name'=>'Mexican Peso','latitude'=>23.6345,'longitude'=>-102.5528],
                // Amerika Tengah & Karibia
                ['name'=>'Guatemala','code'=>'GT','flag'=>'🇬🇹','capital'=>'Guatemala City','region'=>'Americas','population'=>17000000,'gdp'=>85000000000,'inflation'=>8.7,'currency_code'=>'GTQ','currency_name'=>'Guatemalan Quetzal','latitude'=>15.7835,'longitude'=>-90.2308],
                ['name'=>'Honduras','code'=>'HN','flag'=>'🇭🇳','capital'=>'Tegucigalpa','region'=>'Americas','population'=>10000000,'gdp'=>28000000000,'inflation'=>8.5,'currency_code'=>'HNL','currency_name'=>'Honduran Lempira','latitude'=>15.2000,'longitude'=>-86.2419],
                ['name'=>'El Salvador','code'=>'SV','flag'=>'🇸🇻','capital'=>'San Salvador','region'=>'Americas','population'=>6500000,'gdp'=>32000000000,'inflation'=>7.3,'currency_code'=>'USD','currency_name'=>'US Dollar','latitude'=>13.7942,'longitude'=>-88.8965],
                ['name'=>'Nikaragua','code'=>'NI','flag'=>'🇳🇮','capital'=>'Managua','region'=>'Americas','population'=>7000000,'gdp'=>14000000000,'inflation'=>11.0,'currency_code'=>'NIO','currency_name'=>'Nicaraguan Córdoba','latitude'=>12.8654,'longitude'=>-85.2072],
                ['name'=>'Kosta Rika','code'=>'CR','flag'=>'🇨🇷','capital'=>'San José','region'=>'Americas','population'=>5100000,'gdp'=>65000000000,'inflation'=>5.5,'currency_code'=>'CRC','currency_name'=>'Costa Rican Colón','latitude'=>9.7489,'longitude'=>-83.7534],
                ['name'=>'Panama','code'=>'PA','flag'=>'🇵🇦','capital'=>'Panama City','region'=>'Americas','population'=>4300000,'gdp'=>70000000000,'inflation'=>2.9,'currency_code'=>'PAB','currency_name'=>'Panamanian Balboa','latitude'=>8.5380,'longitude'=>-80.7821],
                ['name'=>'Kuba','code'=>'CU','flag'=>'🇨🇺','capital'=>'Havana','region'=>'Americas','population'=>11000000,'gdp'=>100000000000,'inflation'=>45.0,'currency_code'=>'CUP','currency_name'=>'Cuban Peso','latitude'=>21.5218,'longitude'=>-77.7812],
                ['name'=>'Republik Dominika','code'=>'DO','flag'=>'🇩🇴','capital'=>'Santo Domingo','region'=>'Americas','population'=>11000000,'gdp'=>114000000000,'inflation'=>7.8,'currency_code'=>'DOP','currency_name'=>'Dominican Peso','latitude'=>18.7357,'longitude'=>-70.1627],
                ['name'=>'Haiti','code'=>'HT','flag'=>'🇭🇹','capital'=>'Port-au-Prince','region'=>'Americas','population'=>11500000,'gdp'=>14000000000,'inflation'=>25.0,'currency_code'=>'HTG','currency_name'=>'Haitian Gourde','latitude'=>18.9712,'longitude'=>-72.2852],
                ['name'=>'Jamaika','code'=>'JM','flag'=>'🇯🇲','capital'=>'Kingston','region'=>'Americas','population'=>3000000,'gdp'=>15000000000,'inflation'=>10.0,'currency_code'=>'JMD','currency_name'=>'Jamaican Dollar','latitude'=>18.1096,'longitude'=>-77.2975],
                ['name'=>'Trinidad dan Tobago','code'=>'TT','flag'=>'🇹🇹','capital'=>'Port of Spain','region'=>'Americas','population'=>1400000,'gdp'=>25000000000,'inflation'=>5.0,'currency_code'=>'TTD','currency_name'=>'Trinidad and Tobago Dollar','latitude'=>10.6918,'longitude'=>-61.2225],
                // Amerika Selatan
                ['name'=>'Brasil','code'=>'BR','flag'=>'🇧🇷','capital'=>'Brasilia','region'=>'Americas','population'=>215300000,'gdp'=>1920000000000,'inflation'=>4.6,'currency_code'=>'BRL','currency_name'=>'Brazilian Real','latitude'=>-14.2350,'longitude'=>-51.9253],
                ['name'=>'Argentina','code'=>'AR','flag'=>'🇦🇷','capital'=>'Buenos Aires','region'=>'Americas','population'=>46200000,'gdp'=>640000000000,'inflation'=>211.4,'currency_code'=>'ARS','currency_name'=>'Argentine Peso','latitude'=>-38.4161,'longitude'=>-63.6167],
                ['name'=>'Kolombia','code'=>'CO','flag'=>'🇨🇴','capital'=>'Bogota','region'=>'Americas','population'=>51900000,'gdp'=>343000000000,'inflation'=>13.2,'currency_code'=>'COP','currency_name'=>'Colombian Peso','latitude'=>4.5709,'longitude'=>-74.2973],
                ['name'=>'Chili','code'=>'CL','flag'=>'🇨🇱','capital'=>'Santiago','region'=>'Americas','population'=>19500000,'gdp'=>301000000000,'inflation'=>12.8,'currency_code'=>'CLP','currency_name'=>'Chilean Peso','latitude'=>-35.6751,'longitude'=>-71.5430],
                ['name'=>'Peru','code'=>'PE','flag'=>'🇵🇪','capital'=>'Lima','region'=>'Americas','population'=>33000000,'gdp'=>242000000000,'inflation'=>8.5,'currency_code'=>'PEN','currency_name'=>'Peruvian Sol','latitude'=>-9.1900,'longitude'=>-75.0152],
                ['name'=>'Venezuela','code'=>'VE','flag'=>'🇻🇪','capital'=>'Caracas','region'=>'Americas','population'=>28000000,'gdp'=>92000000000,'inflation'=>200.0,'currency_code'=>'VES','currency_name'=>'Venezuelan Bolívar Soberano','latitude'=>6.4238,'longitude'=>-66.5897],
                ['name'=>'Ekuador','code'=>'EC','flag'=>'🇪🇨','capital'=>'Quito','region'=>'Americas','population'=>18000000,'gdp'=>106000000000,'inflation'=>3.5,'currency_code'=>'USD','currency_name'=>'US Dollar','latitude'=>-1.8312,'longitude'=>-78.1834],
                ['name'=>'Bolivia','code'=>'BO','flag'=>'🇧🇴','capital'=>'Sucre','region'=>'Americas','population'=>12000000,'gdp'=>44000000000,'inflation'=>3.6,'currency_code'=>'BOB','currency_name'=>'Bolivian Boliviano','latitude'=>-16.2902,'longitude'=>-63.5887],
                ['name'=>'Paraguay','code'=>'PY','flag'=>'🇵🇾','capital'=>'Asuncion','region'=>'Americas','population'=>7400000,'gdp'=>41000000000,'inflation'=>8.1,'currency_code'=>'PYG','currency_name'=>'Paraguayan Guaraní','latitude'=>-23.4425,'longitude'=>-58.4438],
                ['name'=>'Uruguay','code'=>'UY','flag'=>'🇺🇾','capital'=>'Montevideo','region'=>'Americas','population'=>3500000,'gdp'=>77000000000,'inflation'=>9.1,'currency_code'=>'UYU','currency_name'=>'Uruguayan Peso','latitude'=>-32.5228,'longitude'=>-55.7658],
                ['name'=>'Guyana','code'=>'GY','flag'=>'🇬🇾','capital'=>'Georgetown','region'=>'Americas','population'=>790000,'gdp'=>15000000000,'inflation'=>7.0,'currency_code'=>'GYD','currency_name'=>'Guyanese Dollar','latitude'=>4.8604,'longitude'=>-58.9302],
                ['name'=>'Suriname','code'=>'SR','flag'=>'🇸🇷','capital'=>'Paramaribo','region'=>'Americas','population'=>600000,'gdp'=>3900000000,'inflation'=>55.0,'currency_code'=>'SRD','currency_name'=>'Surinamese Dollar','latitude'=>3.9193,'longitude'=>-56.0278],
                ['name'=>'Belize','code'=>'BZ','flag'=>'🇧🇿','capital'=>'Belmopan','region'=>'Americas','population'=>400000,'gdp'=>2000000000,'inflation'=>4.5,'currency_code'=>'BZD','currency_name'=>'Belize Dollar','latitude'=>17.1899,'longitude'=>-88.4976],
                // Afrika Utara
                ['name'=>'Mesir','code'=>'EG','flag'=>'🇪🇬','capital'=>'Kairo','region'=>'Africa','population'=>104000000,'gdp'=>398000000000,'inflation'=>26.5,'currency_code'=>'EGP','currency_name'=>'Egyptian Pound','latitude'=>26.8206,'longitude'=>30.8025],
                ['name'=>'Aljazair','code'=>'DZ','flag'=>'🇩🇿','capital'=>'Algiers','region'=>'Africa','population'=>45000000,'gdp'=>190000000000,'inflation'=>9.6,'currency_code'=>'DZD','currency_name'=>'Algerian Dinar','latitude'=>28.0339,'longitude'=>1.6596],
                ['name'=>'Maroko','code'=>'MA','flag'=>'🇲🇦','capital'=>'Rabat','region'=>'Africa','population'=>37000000,'gdp'=>130000000000,'inflation'=>8.0,'currency_code'=>'MAD','currency_name'=>'Moroccan Dirham','latitude'=>31.7917,'longitude'=>-7.0926],
                ['name'=>'Tunisia','code'=>'TN','flag'=>'🇹🇳','capital'=>'Tunis','region'=>'Africa','population'=>12000000,'gdp'=>46000000000,'inflation'=>8.3,'currency_code'=>'TND','currency_name'=>'Tunisian Dinar','latitude'=>33.8869,'longitude'=>9.5375],
                ['name'=>'Libya','code'=>'LY','flag'=>'🇱🇾','capital'=>'Tripoli','region'=>'Africa','population'=>7000000,'gdp'=>38000000000,'inflation'=>3.5,'currency_code'=>'LYD','currency_name'=>'Libyan Dinar','latitude'=>26.3351,'longitude'=>17.2283],
                ['name'=>'Sudan','code'=>'SD','flag'=>'🇸🇩','capital'=>'Khartoum','region'=>'Africa','population'=>45000000,'gdp'=>35000000000,'inflation'=>63.0,'currency_code'=>'SDG','currency_name'=>'Sudanese Pound','latitude'=>12.8628,'longitude'=>30.2176],
                // Afrika Barat
                ['name'=>'Nigeria','code'=>'NG','flag'=>'🇳🇬','capital'=>'Abuja','region'=>'Africa','population'=>218000000,'gdp'=>477000000000,'inflation'=>24.4,'currency_code'=>'NGN','currency_name'=>'Nigerian Naira','latitude'=>9.0820,'longitude'=>8.6753],
                ['name'=>'Ghana','code'=>'GH','flag'=>'🇬🇭','capital'=>'Accra','region'=>'Africa','population'=>33000000,'gdp'=>72000000000,'inflation'=>54.1,'currency_code'=>'GHS','currency_name'=>'Ghanaian Cedi','latitude'=>7.9465,'longitude'=>-1.0232],
                ['name'=>'Senegal','code'=>'SN','flag'=>'🇸🇳','capital'=>'Dakar','region'=>'Africa','population'=>17000000,'gdp'=>27000000000,'inflation'=>9.7,'currency_code'=>'XOF','currency_name'=>'West African CFA Franc','latitude'=>14.4974,'longitude'=>-14.4524],
                ['name'=>'Mali','code'=>'ML','flag'=>'🇲🇱','capital'=>'Bamako','region'=>'Africa','population'=>22000000,'gdp'=>18000000000,'inflation'=>7.0,'currency_code'=>'XOF','currency_name'=>'West African CFA Franc','latitude'=>17.5707,'longitude'=>-3.9962],
                ['name'=>'Burkina Faso','code'=>'BF','flag'=>'🇧🇫','capital'=>'Ouagadougou','region'=>'Africa','population'=>22000000,'gdp'=>19000000000,'inflation'=>14.5,'currency_code'=>'XOF','currency_name'=>'West African CFA Franc','latitude'=>12.3642,'longitude'=>-1.5275],
                ['name'=>'Niger','code'=>'NE','flag'=>'🇳🇪','capital'=>'Niamey','region'=>'Africa','population'=>25000000,'gdp'=>14000000000,'inflation'=>5.5,'currency_code'=>'XOF','currency_name'=>'West African CFA Franc','latitude'=>17.6078,'longitude'=>8.0817],
                ['name'=>'Guinea','code'=>'GN','flag'=>'🇬🇳','capital'=>'Conakry','region'=>'Africa','population'=>13000000,'gdp'=>15000000000,'inflation'=>12.0,'currency_code'=>'GNF','currency_name'=>'Guinean Franc','latitude'=>11.8037,'longitude'=>-15.1804],
                ['name'=>'Pantai Gading','code'=>'CI','flag'=>'🇨🇮','capital'=>'Yamoussoukro','region'=>'Africa','population'=>27000000,'gdp'=>70000000000,'inflation'=>6.2,'currency_code'=>'XOF','currency_name'=>'West African CFA Franc','latitude'=>7.5400,'longitude'=>-5.5471],
                ['name'=>'Liberia','code'=>'LR','flag'=>'🇱🇷','capital'=>'Monrovia','region'=>'Africa','population'=>5300000,'gdp'=>3900000000,'inflation'=>8.0,'currency_code'=>'LRD','currency_name'=>'Liberian Dollar','latitude'=>6.4281,'longitude'=>-9.4295],
                ['name'=>'Sierra Leone','code'=>'SL','flag'=>'🇸🇱','capital'=>'Freetown','region'=>'Africa','population'=>8200000,'gdp'=>4100000000,'inflation'=>43.5,'currency_code'=>'SLL','currency_name'=>'Sierra Leonean Leone','latitude'=>8.4606,'longitude'=>-11.7799],
                ['name'=>'Benin','code'=>'BJ','flag'=>'🇧🇯','capital'=>'Porto-Novo','region'=>'Africa','population'=>13000000,'gdp'=>17000000000,'inflation'=>1.7,'currency_code'=>'XOF','currency_name'=>'West African CFA Franc','latitude'=>9.3077,'longitude'=>2.3158],
                ['name'=>'Togo','code'=>'TG','flag'=>'🇹🇬','capital'=>'Lome','region'=>'Africa','population'=>8700000,'gdp'=>8400000000,'inflation'=>8.0,'currency_code'=>'XOF','currency_name'=>'West African CFA Franc','latitude'=>8.6195,'longitude'=>0.8248],
                ['name'=>'Mauritania','code'=>'MR','flag'=>'🇲🇷','capital'=>'Nouakchott','region'=>'Africa','population'=>4700000,'gdp'=>10000000000,'inflation'=>9.5,'currency_code'=>'MRU','currency_name'=>'Mauritanian Ouguiya','latitude'=>21.0079,'longitude'=>-10.9408],
                ['name'=>'Guinea-Bissau','code'=>'GW','flag'=>'🇬🇼','capital'=>'Bissau','region'=>'Africa','population'=>2000000,'gdp'=>1400000000,'inflation'=>5.6,'currency_code'=>'XOF','currency_name'=>'West African CFA Franc','latitude'=>11.8037,'longitude'=>-15.1804],
                ['name'=>'Gambia','code'=>'GM','flag'=>'🇬🇲','capital'=>'Banjul','region'=>'Africa','population'=>2600000,'gdp'=>2100000000,'inflation'=>11.3,'currency_code'=>'GMD','currency_name'=>'Gambian Dalasi','latitude'=>13.4432,'longitude'=>-15.3101],
                ['name'=>'Tanjung Verde','code'=>'CV','flag'=>'🇨🇻','capital'=>'Praia','region'=>'Africa','population'=>560000,'gdp'=>2000000000,'inflation'=>6.0,'currency_code'=>'CVE','currency_name'=>'Cape Verdean Escudo','latitude'=>16.0020,'longitude'=>-24.0132],
                // Afrika Tengah & Timur
                ['name'=>'Ethiopia','code'=>'ET','flag'=>'🇪🇹','capital'=>'Addis Ababa','region'=>'Africa','population'=>124000000,'gdp'=>127000000000,'inflation'=>28.0,'currency_code'=>'ETB','currency_name'=>'Ethiopian Birr','latitude'=>9.1450,'longitude'=>40.4897],
                ['name'=>'Kenya','code'=>'KE','flag'=>'🇰🇪','capital'=>'Nairobi','region'=>'Africa','population'=>55000000,'gdp'=>106000000000,'inflation'=>7.9,'currency_code'=>'KES','currency_name'=>'Kenyan Shilling','latitude'=>-0.0236,'longitude'=>37.9062],
                ['name'=>'Tanzania','code'=>'TZ','flag'=>'🇹🇿','capital'=>'Dodoma','region'=>'Africa','population'=>63000000,'gdp'=>76000000000,'inflation'=>4.3,'currency_code'=>'TZS','currency_name'=>'Tanzanian Shilling','latitude'=>-6.3690,'longitude'=>34.8888],
                ['name'=>'Uganda','code'=>'UG','flag'=>'🇺🇬','capital'=>'Kampala','region'=>'Africa','population'=>48000000,'gdp'=>45000000000,'inflation'=>8.0,'currency_code'=>'UGX','currency_name'=>'Ugandan Shilling','latitude'=>1.3733,'longitude'=>32.2903],
                ['name'=>'Rwanda','code'=>'RW','flag'=>'🇷🇼','capital'=>'Kigali','region'=>'Africa','population'=>14000000,'gdp'=>13000000000,'inflation'=>17.8,'currency_code'=>'RWF','currency_name'=>'Rwandan Franc','latitude'=>-1.9403,'longitude'=>29.8739],
                ['name'=>'Burundi','code'=>'BI','flag'=>'🇧🇮','capital'=>'Gitega','region'=>'Africa','population'=>12000000,'gdp'=>3200000000,'inflation'=>27.0,'currency_code'=>'BIF','currency_name'=>'Burundian Franc','latitude'=>-3.3731,'longitude'=>29.9189],
                ['name'=>'Somalia','code'=>'SO','flag'=>'🇸🇴','capital'=>'Mogadishu','region'=>'Africa','population'=>18000000,'gdp'=>7600000000,'inflation'=>6.0,'currency_code'=>'SOS','currency_name'=>'Somali Shilling','latitude'=>5.1521,'longitude'=>46.1996],
                ['name'=>'Eritrea','code'=>'ER','flag'=>'🇪🇷','capital'=>'Asmara','region'=>'Africa','population'=>3600000,'gdp'=>2100000000,'inflation'=>4.0,'currency_code'=>'ERN','currency_name'=>'Eritrean Nakfa','latitude'=>15.1794,'longitude'=>39.7823],
                ['name'=>'Djibouti','code'=>'DJ','flag'=>'🇩🇯','capital'=>'Djibouti','region'=>'Africa','population'=>1000000,'gdp'=>3800000000,'inflation'=>7.0,'currency_code'=>'DJF','currency_name'=>'Djiboutian Franc','latitude'=>11.8251,'longitude'=>42.5903],
                ['name'=>'Sudan Selatan','code'=>'SS','flag'=>'🇸🇸','capital'=>'Juba','region'=>'Africa','population'=>11000000,'gdp'=>4500000000,'inflation'=>102.0,'currency_code'=>'SSP','currency_name'=>'South Sudanese Pound','latitude'=>6.8770,'longitude'=>31.3070],
                ['name'=>'Chad','code'=>'TD','flag'=>'🇹🇩','capital'=>"N'Djamena",'region'=>'Africa','population'=>17000000,'gdp'=>12000000000,'inflation'=>3.0,'currency_code'=>'XAF','currency_name'=>'Central African CFA Franc','latitude'=>15.4542,'longitude'=>18.7322],
                ['name'=>'Kamerun','code'=>'CM','flag'=>'🇨🇲','capital'=>'Yaounde','region'=>'Africa','population'=>28000000,'gdp'=>44000000000,'inflation'=>6.2,'currency_code'=>'XAF','currency_name'=>'Central African CFA Franc','latitude'=>3.8480,'longitude'=>11.5021],
                ['name'=>'Republik Afrika Tengah','code'=>'CF','flag'=>'🇨🇫','capital'=>'Bangui','region'=>'Africa','population'=>5000000,'gdp'=>2600000000,'inflation'=>5.3,'currency_code'=>'XAF','currency_name'=>'Central African CFA Franc','latitude'=>6.6111,'longitude'=>20.9394],
                ['name'=>'Kongo (DRC)','code'=>'CD','flag'=>'🇨🇩','capital'=>'Kinshasa','region'=>'Africa','population'=>100000000,'gdp'=>64000000000,'inflation'=>16.0,'currency_code'=>'CDF','currency_name'=>'Congolese Franc','latitude'=>-4.0383,'longitude'=>21.7587],
                ['name'=>'Republik Kongo','code'=>'CG','flag'=>'🇨🇬','capital'=>'Brazzaville','region'=>'Africa','population'=>6000000,'gdp'=>12000000000,'inflation'=>4.3,'currency_code'=>'XAF','currency_name'=>'Central African CFA Franc','latitude'=>-0.2280,'longitude'=>15.8277],
                ['name'=>'Gabon','code'=>'GA','flag'=>'🇬🇦','capital'=>'Libreville','region'=>'Africa','population'=>2300000,'gdp'=>20000000000,'inflation'=>4.3,'currency_code'=>'XAF','currency_name'=>'Central African CFA Franc','latitude'=>-0.8037,'longitude'=>11.6094],
                ['name'=>'Guinea Khatulistiwa','code'=>'GQ','flag'=>'🇬🇶','capital'=>'Malabo','region'=>'Africa','population'=>1500000,'gdp'=>12000000000,'inflation'=>5.0,'currency_code'=>'XAF','currency_name'=>'Central African CFA Franc','latitude'=>1.6508,'longitude'=>10.2679],
                ['name'=>'Sao Tome dan Principe','code'=>'ST','flag'=>'🇸🇹','capital'=>'São Tomé','region'=>'Africa','population'=>220000,'gdp'=>550000000,'inflation'=>17.5,'currency_code'=>'STN','currency_name'=>'São Tomé and Príncipe Dobra','latitude'=>0.1864,'longitude'=>6.6131],
                // Afrika Selatan
                ['name'=>'Afrika Selatan','code'=>'ZA','flag'=>'🇿🇦','capital'=>'Pretoria','region'=>'Africa','population'=>60000000,'gdp'=>396000000000,'inflation'=>6.9,'currency_code'=>'ZAR','currency_name'=>'South African Rand','latitude'=>-30.5595,'longitude'=>22.9375],
                ['name'=>'Mozambik','code'=>'MZ','flag'=>'🇲🇿','capital'=>'Maputo','region'=>'Africa','population'=>33000000,'gdp'=>17000000000,'inflation'=>10.8,'currency_code'=>'MZN','currency_name'=>'Mozambican Metical','latitude'=>-18.6657,'longitude'=>35.5296],
                ['name'=>'Madagaskar','code'=>'MG','flag'=>'🇲🇬','capital'=>'Antananarivo','region'=>'Africa','population'=>28000000,'gdp'=>14000000000,'inflation'=>10.1,'currency_code'=>'MGA','currency_name'=>'Malagasy Ariary','latitude'=>-18.7669,'longitude'=>46.8691],
                ['name'=>'Angola','code'=>'AO','flag'=>'🇦🇴','capital'=>'Luanda','region'=>'Africa','population'=>35000000,'gdp'=>108000000000,'inflation'=>21.4,'currency_code'=>'AOA','currency_name'=>'Angolan Kwanza','latitude'=>-11.2027,'longitude'=>17.8739],
                ['name'=>'Zambia','code'=>'ZM','flag'=>'🇿🇲','capital'=>'Lusaka','region'=>'Africa','population'=>19000000,'gdp'=>30000000000,'inflation'=>9.7,'currency_code'=>'ZMW','currency_name'=>'Zambian Kwacha','latitude'=>-13.1339,'longitude'=>27.8493],
                ['name'=>'Zimbabwe','code'=>'ZW','flag'=>'🇿🇼','capital'=>'Harare','region'=>'Africa','population'=>16000000,'gdp'=>28500000000,'inflation'=>55.0,'currency_code'=>'ZWL','currency_name'=>'Zimbabwean Dollar','latitude'=>-19.0154,'longitude'=>29.1549],
                ['name'=>'Malawi','code'=>'MW','flag'=>'🇲🇼','capital'=>'Lilongwe','region'=>'Africa','population'=>20000000,'gdp'=>14000000000,'inflation'=>26.7,'currency_code'=>'MWK','currency_name'=>'Malawian Kwacha','latitude'=>-13.2543,'longitude'=>34.3015],
                ['name'=>'Botswana','code'=>'BW','flag'=>'🇧🇼','capital'=>'Gaborone','region'=>'Africa','population'=>2600000,'gdp'=>21000000000,'inflation'=>14.1,'currency_code'=>'BWP','currency_name'=>'Botswana Pula','latitude'=>-22.3285,'longitude'=>24.6849],
                ['name'=>'Namibia','code'=>'NA','flag'=>'🇳🇦','capital'=>'Windhoek','region'=>'Africa','population'=>2600000,'gdp'=>12000000000,'inflation'=>7.2,'currency_code'=>'NAD','currency_name'=>'Namibian Dollar','latitude'=>-22.9576,'longitude'=>18.4904],
                ['name'=>'Eswatini','code'=>'SZ','flag'=>'🇸🇿','capital'=>'Mbabane','region'=>'Africa','population'=>1200000,'gdp'=>5000000000,'inflation'=>6.9,'currency_code'=>'SZL','currency_name'=>'Swazi Lilangeni','latitude'=>-26.5225,'longitude'=>31.4659],
                ['name'=>'Lesotho','code'=>'LS','flag'=>'🇱🇸','capital'=>'Maseru','region'=>'Africa','population'=>2200000,'gdp'=>3000000000,'inflation'=>7.5,'currency_code'=>'LSL','currency_name'=>'Lesotho Loti','latitude'=>-29.6100,'longitude'=>28.2336],
                ['name'=>'Komoro','code'=>'KM','flag'=>'🇰🇲','capital'=>'Moroni','region'=>'Africa','population'=>870000,'gdp'=>1300000000,'inflation'=>9.6,'currency_code'=>'KMF','currency_name'=>'Comorian Franc','latitude'=>-11.8750,'longitude'=>43.8722],
                ['name'=>'Mauritius','code'=>'MU','flag'=>'🇲🇺','capital'=>'Port Louis','region'=>'Africa','population'=>1300000,'gdp'=>14000000000,'inflation'=>10.7,'currency_code'=>'MUR','currency_name'=>'Mauritian Rupee','latitude'=>-20.3484,'longitude'=>57.5522],
                ['name'=>'Seychelles','code'=>'SC','flag'=>'🇸🇨','capital'=>'Victoria','region'=>'Africa','population'=>100000,'gdp'=>1900000000,'inflation'=>2.6,'currency_code'=>'SCR','currency_name'=>'Seychellois Rupee','latitude'=>-4.6796,'longitude'=>55.4920],
                // Oseania
                ['name'=>'Australia','code'=>'AU','flag'=>'🇦🇺','capital'=>'Canberra','region'=>'Oceania','population'=>26005540,'gdp'=>1718090000000,'inflation'=>4.1,'currency_code'=>'AUD','currency_name'=>'Australian Dollar','latitude'=>-25.2744,'longitude'=>133.7751],
                ['name'=>'Selandia Baru','code'=>'NZ','flag'=>'🇳🇿','capital'=>'Wellington','region'=>'Oceania','population'=>5100000,'gdp'=>249000000000,'inflation'=>6.7,'currency_code'=>'NZD','currency_name'=>'New Zealand Dollar','latitude'=>-40.9006,'longitude'=>174.8860],
                ['name'=>'Papua Nugini','code'=>'PG','flag'=>'🇵🇬','capital'=>'Port Moresby','region'=>'Oceania','population'=>10000000,'gdp'=>27000000000,'inflation'=>5.5,'currency_code'=>'PGK','currency_name'=>'Papua New Guinean Kina','latitude'=>-6.3150,'longitude'=>143.9555],
                ['name'=>'Fiji','code'=>'FJ','flag'=>'🇫🇯','capital'=>'Suva','region'=>'Oceania','population'=>930000,'gdp'=>4400000000,'inflation'=>3.8,'currency_code'=>'FJD','currency_name'=>'Fijian Dollar','latitude'=>-17.7134,'longitude'=>178.0650],
                ['name'=>'Kepulauan Solomon','code'=>'SB','flag'=>'🇸🇧','capital'=>'Honiara','region'=>'Oceania','population'=>720000,'gdp'=>1400000000,'inflation'=>6.0,'currency_code'=>'SBD','currency_name'=>'Solomon Islands Dollar','latitude'=>-9.6457,'longitude'=>160.1562],
                ['name'=>'Vanuatu','code'=>'VU','flag'=>'🇻🇺','capital'=>'Port Vila','region'=>'Oceania','population'=>320000,'gdp'=>950000000,'inflation'=>4.5,'currency_code'=>'VUV','currency_name'=>'Vanuatu Vatu','latitude'=>-15.3767,'longitude'=>166.9592],
                ['name'=>'Samoa','code'=>'WS','flag'=>'🇼🇸','capital'=>'Apia','region'=>'Oceania','population'=>220000,'gdp'=>850000000,'inflation'=>11.0,'currency_code'=>'WST','currency_name'=>'Samoan Tālā','latitude'=>-13.7590,'longitude'=>-172.1046],
                ['name'=>'Tonga','code'=>'TO','flag'=>'🇹🇴','capital'=>"Nuku'alofa",'region'=>'Oceania','population'=>100000,'gdp'=>490000000,'inflation'=>11.0,'currency_code'=>'TOP','currency_name'=>"Tongan Pa'anga",'latitude'=>-21.1789,'longitude'=>-175.1982],
                ['name'=>'Kiribati','code'=>'KI','flag'=>'🇰🇮','capital'=>'South Tarawa','region'=>'Oceania','population'=>120000,'gdp'=>200000000,'inflation'=>2.5,'currency_code'=>'AUD','currency_name'=>'Australian Dollar','latitude'=>-3.3704,'longitude'=>-168.7340],
                ['name'=>'Mikronesia','code'=>'FM','flag'=>'🇫🇲','capital'=>'Palikir','region'=>'Oceania','population'=>115000,'gdp'=>430000000,'inflation'=>4.0,'currency_code'=>'USD','currency_name'=>'US Dollar','latitude'=>7.4256,'longitude'=>150.5508],
                ['name'=>'Palau','code'=>'PW','flag'=>'🇵🇼','capital'=>'Ngerulmud','region'=>'Oceania','population'=>18000,'gdp'=>260000000,'inflation'=>3.0,'currency_code'=>'USD','currency_name'=>'US Dollar','latitude'=>7.5150,'longitude'=>134.5825],
                ['name'=>'Kepulauan Marshall','code'=>'MH','flag'=>'🇲🇭','capital'=>'Majuro','region'=>'Oceania','population'=>42000,'gdp'=>240000000,'inflation'=>3.0,'currency_code'=>'USD','currency_name'=>'US Dollar','latitude'=>7.1315,'longitude'=>171.1845],
                ['name'=>'Nauru','code'=>'NR','flag'=>'🇳🇷','capital'=>'Yaren','region'=>'Oceania','population'=>10000,'gdp'=>130000000,'inflation'=>2.5,'currency_code'=>'AUD','currency_name'=>'Australian Dollar','latitude'=>-0.5228,'longitude'=>166.9315],
                ['name'=>'Tuvalu','code'=>'TV','flag'=>'🇹🇻','capital'=>'Funafuti','region'=>'Oceania','population'=>11000,'gdp'=>60000000,'inflation'=>3.0,'currency_code'=>'AUD','currency_name'=>'Australian Dollar','latitude'=>-7.1095,'longitude'=>179.1940],
            ];
        }

        // Insert countries while ensuring unique 2-letter codes
        $insertedCodes = [];

        foreach ($countries as $c) {
            if (empty($c['code']) || strlen($c['code']) !== 2 || in_array($c['code'], $insertedCodes)) {
                continue;
            }
            $countryModel = Country::create($c);
            $insertedCodes[] = $c['code'];

            // Seed an initial risk score for each country so the map has data immediately
            $wScore = rand(20, 80);
            $iScore = rand(20, 80);
            $cScore = rand(20, 80);
            $nScore = rand(20, 80);
            $tScore = ($wScore * 0.3) + ($iScore * 0.2) + ($cScore * 0.1) + ($nScore * 0.4);

            DB::table('risk_scores')->insert([
                'country_id' => $countryModel->id,
                'weather_score' => $wScore,
                'inflation_score' => $iScore,
                'currency_score' => $cScore,
                'news_score' => $nScore,
                'total_score' => round($tScore, 1),
                'calculated_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Seed Ports
        $ports = [
            [
                'name' => 'Port of Tanjung Priok',
                'code' => 'IDTPP',
                'country_code' => 'ID',
                'latitude' => -6.1033,
                'longitude' => 106.8792,
                'congestion_status' => 'Medium',
            ],
            [
                'name' => 'Port of Shanghai',
                'code' => 'CNSHA',
                'country_code' => 'CN',
                'latitude' => 31.2304,
                'longitude' => 121.4737,
                'congestion_status' => 'High',
            ],
            [
                'name' => 'Port of Los Angeles',
                'code' => 'USLAX',
                'country_code' => 'US',
                'latitude' => 33.7431,
                'longitude' => -118.2673,
                'congestion_status' => 'Low',
            ],
            [
                'name' => 'Port of Singapore',
                'code' => 'SGSIN',
                'country_code' => 'SG',
                'latitude' => 1.274,
                'longitude' => 103.801,
                'congestion_status' => 'Medium',
            ],
            [
                'name' => 'Port of Rotterdam',
                'code' => 'NLRTM',
                'country_code' => 'NL',
                'latitude' => 51.9244,
                'longitude' => 4.4777,
                'congestion_status' => 'Low',
            ],
            [
                'name' => 'Port of Vladivostok',
                'code' => 'RUVLA',
                'country_code' => 'RU',
                'latitude' => 43.1198,
                'longitude' => 131.8869,
                'congestion_status' => 'High',
            ],
            [
                'name' => 'Port of Tokyo',
                'code' => 'JPTYO',
                'country_code' => 'JP',
                'latitude' => 35.6268,
                'longitude' => 139.7906,
                'congestion_status' => 'Low',
            ],
            [
                'name' => 'Port of Hamburg',
                'code' => 'DEHAM',
                'country_code' => 'DE',
                'latitude' => 53.5458,
                'longitude' => 9.9658,
                'congestion_status' => 'Medium',
            ],
            [
                'name' => 'Port of Melbourne',
                'code' => 'AUMEL',
                'country_code' => 'AU',
                'latitude' => -37.8286,
                'longitude' => 144.9142,
                'congestion_status' => 'Low',
            ]
        ];

        foreach ($ports as $p) {
            Port::create($p);
        }

        // 4. Seed Sentiment Lexicon Words
        $positiveWords = [
            'growth', 'increase', 'profit', 'stable', 'improve', 'success', 'safe', 'gain',
            'positive', 'boom', 'recovery', 'upward', 'strengthen', 'alliance', 'cooperation',
            'peace', 'settlement', 'grow', 'rise', 'stabilize', 'resolve', 'progress', 'boost'
        ];

        $negativeWords = [
            'war', 'crisis', 'inflation', 'delay', 'disaster', 'decrease', 'loss', 'danger',
            'negative', 'drop', 'tariff', 'conflict', 'tension', 'ban', 'sanction', 'recession',
            'strike', 'storm', 'typhoon', 'flood', 'earthquake', 'decline', 'collapse', 'threat',
            'disrupt', 'congest', 'blockade', 'protest', 'clash', 'embargo'
        ];

        foreach ($positiveWords as $word) {
            DB::table('positive_words')->insert([
                'word' => $word,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($negativeWords as $word) {
            DB::table('negative_words')->insert([
                'word' => $word,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
