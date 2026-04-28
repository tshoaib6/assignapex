<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pixel;
use App\Models\RegionCity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    public function index()
    {
        return view('import.index');
    }

    // Helper function to translate Arabic to English
    private function translateArabicToEnglish($text)
    {
        if (empty($text)) return null;
        $text = trim($text);

        // Basic mapping for common Arabic region/city names to English
        $translations = [
            'الخضراء' => 'Al Khadra',
            'الجراديه' => 'Al Jaradiyah',
            'صامطه' => 'Samtah',
            'الركوبه' => 'Al Rakubah',
            'خبت الخارش  ومزارعة' => 'Khabt Al Kharish',
            'خبت الخارش ومزارعة' => 'Khabt Al Kharish', // Variation without double space
            'فرسان' => 'Farasan',
            'احد المسارحه' => 'Ahad Al Masarihah',
            'رماده' => 'Ramadah',
            'المضايا' => 'Al Madaya',
            'الحصمة' => 'Al Hasmah',
            'جيزان' => 'Jazan',
            'مزهره' => 'Mizhirah',
            'العسيله' => 'Al Usaylah',
            'محليه' => 'Mahliyah',
            'الخرادله' => 'Al Kharadilah',
            'ابو عريش' => 'Abu Arish',
            'البديع والقرفي' => 'Al Badi Wal Qarfi',
            'الشواجره' => 'Al Shawajirah',
            'العروق الشمالية' => 'Al Uruq Al Shamaliyah',
            'الوديعه' => 'Al Wadiah',
            'حاكمه' => 'Hakimah',
            'العارضه' => 'Al Aridoh',
            'الظبيه' => 'Adh Dhabiyah',
            'ضمد' => 'Damad',
            'صبياء' => 'Sabya',
            'الحسينى' => 'Al Husayni',
            'الشقيرى' => 'Al Shuqayri',
            'ابوالسلع' => 'Abu Al Sala',
            'العيدابي' => 'Al Edabi',
            'العاليه والخضراء' => 'Al Aliyah Wal Khadra',
            'الدائر' => 'Ad Dayer',
            'بيش' => 'Baish',
            'نجران' => 'Najran',
            'المطعن' => 'Al Matan',
            'مسليه' => 'Masliyah',
            'شروره' => 'Sharurah',
            'خباش' => 'Khabash',
            'ظهران الجنوب' => 'Dhahran Al Janub',
            'ابو السداد' => 'Abu Al Saddad',
            'الدرب' => 'Ad Darb',
            'المشعليه' => 'Al Mishaliyah',
            'حبونا' => 'Hubuna',
            'سراة عبيدة' => 'Sarat Abidah',
            'الواديين' => 'Al Wadiyayn',
            'احد رفيده' => 'Ahad Rafidah',
            'خميس مشيط' => 'Khamis Mushait',
            'ابها' => 'Abha',
            'بحر ابوسكينه (خميس البحر)' => 'Bahr Abu Sukaynah',
            'سنعبره' => 'Sanabirah',
            'الجنفور' => 'Al Janfur',
            'محائل' => 'Mahayil',
            'فرعه طريب' => 'Farat Tathlith',
            'الاثنين' => 'Al Ithnayn',
            'الصفه' => 'As Suffah',
            'تنومة' => 'Tanomah',
            'القوز' => 'Al Qawz',
            'النماص' => 'Al Namas',
            'المجارده' => 'Al Majardah',
            'القنفذه' => 'Al Qunfudhah',
            'تثليث' => 'Tathlith',
            'ال ريحان' => 'Al Rayhan',
            'العلاية' => 'Al Alayah',
            'المظيلف' => 'Al Mudhaylif',
            'نمره' => 'Namrah',
            'المخواه' => 'Al Makhwah',
            'بيشه' => 'Bisha',
            'بلجرشى' => 'Baljurashi',
            'قلوه' => 'Qilwah',
            'الباحة' => 'Al Baha',
            'بني حسن' => 'Bani Hassan',
            'المندق' => 'Al Mandaq',
            'الليث' => 'Al Lith',
            'العقيق' => 'Al Aqiq',
            'وادي الدواسر' => 'Wadi Ad Dawasir',
            'أضم' => 'Adam',
            'السليل' => 'As Sulayyil',
            'الشعيبه' => 'Shoaiba',
            'قياء' => 'Qia',
            'تربه' => 'Turabah',
            'رنيه' => 'Ranyah',
            'مكه المكرمه' => 'Makkah',
            'قرية شعب مكا' => 'Shaab Makkah',
            'الطائف' => 'Taif',
            'ملكان' => 'Malkan',
            'جدة' => 'Jeddah',
            'الرميده' => 'Al Rumaydah',
            'الهدا' => 'Al Hada',
            'الحويه' => 'Al Hawiyah',
            'بحره' => 'Bahrah',
            'السيل الصغير' => 'As Sayl As Saghir',
            'العرفاء' => 'Al Arfa',
            'ام الاوقب' => 'Umm Al Awqab',
            'الجموم' => 'Jumum',
            'السيل' => 'As Sayl',
            'عشيره' => 'Ashirah',
            'الخرمه' => 'Al Khurma',
            'ذهبان' => 'Dhahban',
            'البديع الشمالي' => 'Al Badi Al Shamali',
            'غران' => 'Ghuran',
            'خليص' => 'Khulais',
            'ليلى' => 'Layla',
            'ثول' => 'Thuwal',
            'المويه' => 'Al Muwayh',
            'مدينة الملك عبدالله الإقتصاديه' => 'KAEC',
            'أم الدوم' => 'Umm Al Dum',
            'رابغ' => 'Rabigh',
            'مستوره' => 'Masturah',
            'حوطه بنى تميم' => 'Hotat Bani Tamim',
            'مهد الذهب' => 'Mahd Al Dhahab',
            'الحريق' => 'Al Hariq',
            'الرويضه' => 'Ar Ruwaydah',
            'بدر' => 'Badr',
            'عفيف' => 'Afif',
            'قاعده الامير سلطان' => 'Prince Sultan Air Base',
            'الدلم' => 'Ad Dilam',
            'ينبع الصناعيه' => 'Yanbu Industrial',
            'القويعيه' => 'Al Quwaiiyah',
            'السيح' => 'As Sayh',
            'الهياثم' => 'Al Hayathim',
            'ينبع' => 'Yanbu',
            'النايفية' => 'An Nayfiyah',
            'البجاديه' => 'Al Bijadiyah',
            'الشديده' => 'Ash Shadidah',
            'الحائر' => 'Al Hair',
            'الرياض' => 'Riyadh',
            'العماجية' => 'Al Amajiyah',
            'الدوادمى' => 'Dawadmi',
            'المدينه المنوره' => 'Madinah',
            'المزاحميه' => 'Al Muzahmiya',
            'مزارع وادي العاقول' => 'Wadi Al Aqul',
            'ضرما' => 'Dhurma',
            'الدرعيه' => 'Diriyah',
            'عرقه' => 'Irqah',
            'ضريه' => 'Dhariyah',
            'الحناكيه' => 'Al Hinakiyah',
            'العيينة' => 'Al Uyaynah',
            'الجبيله' => 'Al Jubaylah',
            'بنبان' => 'Banban',
            'كليه الملك عبدالعزيز الحربيه' => 'King Abdulaziz Military College',
            'حريملاء' => 'Huraymila',
            'مرات' => 'Marat',
            'املج' => 'Umluj',
            'ساجر' => 'Sajir',
            'شقراء' => 'Shaqra',
            'ملهم' => 'Malham',
            'العيص' => 'Al Ais',
            'ثادق' => 'Thadiq',
            'الغويبه' => 'Al Ghuwaybah',
            'الهفوف' => 'Hofuf',
            'دخنه' => 'Dukhna',
            'الطرف' => 'At Taraf',
            'المبرز' => 'Al Mubarraz',
            'المنيزله' => 'Al Munaizilah',
            'الجفر' => 'Al Jafr',
            'الجشه' => 'Al Jishah',
            'الجبيل' => 'Jubail',
            'المنصوره' => 'Al Mansurah',
            'الحوطه' => 'Al Hotah',
            'المركز' => 'Al Markaz',
            'البطاليه' => 'Al Battaliyah',
            'الحليله' => 'Al Hulaylah',
            'القاره' => 'Al Qarah',
            'التويثير' => 'At Tuwaythir',
            'العمران' => 'Al Umran',
            'الكلابيه' => 'Al Kilabiyah',
            'رماح' => 'Rumah',
            'اسكان الكلابيه' => 'Iskan Al Kilabiyah',
            'الشقيق' => 'Ash Shuqayq',
            'الجرن' => 'Al Jarn',
            'القرين' => 'Al Qurayn',
            'حوطه سدير' => 'Hotat Sudair',
            'الثمد' => 'Ath Thamad',
            'روضه سدير' => 'Rawdat Sudair',
            'العيون' => 'Al Uyun',
            'تمير' => 'Tumair',
            'خيبر' => 'Khaybar',
            'قصرابن عقيل' => 'Qasr Ibn Aqeel',
            'عقله الصقور' => 'Uglat As Sugour',
            'النبهانيه' => 'Al Nabhaniyah',
            'الرس' => 'Ar Rass',
            'المذنب' => 'Al Mithnab',
            'المجمعه' => 'Al Majmaah',
            'البدائع' => 'Al Badaya',
            'عنيزه' => 'Unaizah',
            'حرمه' => 'Harmah',
            'بقيق' => 'Abqaiq',
            'الغاط' => 'Al Ghat',
            'رياض الخبراء' => 'Riyadh Al Khabra',
            'الحائط' => 'Al Hait',
            'الخبراء' => 'Al Khabra',
            'الهلاليه' => 'Al Hilaliyah',
            'البكيريه' => 'Al Bukayriyah',
            'الخبر' => 'Khobar',
            'بريده' => 'Buraydah',
            'الزلفى' => 'Zulfi',
            'الشماسيه' => 'Al Shimasiyah',
            'الدمام' => 'Dammam',
            'المليداء' => 'Al Mulayda',
            'الظهران' => 'Dhahran',
            'الثقبه' => 'Thuqbah',
            'الشقه' => 'Ash Shiqqah',
            'الوجه' => 'Al Wajh',
            'عيون الجواء' => 'Uyun AlJiwa',
            'الارطاويه' => 'Al Artawiyah',
            'النابيه' => 'An Nabiyah',
            'سيهات' => 'Saihat',
            'الاوجام' => 'Al Awjam',
            'الجش' => 'Al Jish',
            'ام الحمام' => 'Umm Al Hamam',
            'عنك' => 'Anak',
            'الجاروديه' => 'Al Jarudiyah',
            'القطيف' => 'Qatif',
            'تاروت' => 'Tarout',
            'العلا' => 'Al Ula',
            'القديح' => 'Al Qudayh',
            'التوبى' => 'At Tobi',
            'العواميه' => 'Al Awamiyah',
            'صفوى' => 'Safwa',
            'القواره' => 'Al Quwarah',
            'ام الساهك' => 'Umm As Sahik',
            'عين بن فهيد' => 'Ayn Ibn Fuhayd',
            'رحيمة' => 'Rahima',
            'الغزالة' => 'Al Ghazalah',
            'الشملي' => 'Ash Shamli',
            'مدينه الجبيل الصناعيه' => 'Jubail Industrial City',
            'محطه التحليه' => 'Desalination Plant',
            'الشنان' => 'Al Shinan',
            'مريخ' => 'Muraikh',
            'حائل' => 'Hail',
            'قبه' => 'Qiba',
            'الاجفر' => 'Al Ajfar',
            'النعيريه' => 'Nairyah',
            'ضباء' => 'Duba',
            'قريه العليا' => 'Qaryat Al Ulya',
            'تيماء' => 'Tayma',
            'مدينة الملك خالد العسكرية' => 'KKMC',
            'منيفة القاعد' => 'Munifah Al Qaid',
            'بقعاء' => 'Baqa',
            'جبة' => 'Jubbah',
            'الذيبيه' => 'Adh Dhibiyah',
            'تربة' => 'Turbah', // Note: Duplicate key 'تربة' exists, this one is for Hail/North
            'حفر الباطن' => 'Hafar Al Batin',
            'القيصومه' => 'Al Qaysumah',
            'الخفجى' => 'Khafji',
            'تبوك' => 'Tabuk',
            'البدع' => 'Al Bad',
            'روضة هباس' => 'Rawdat Habbas',
            'رفحاء' => 'Rafha',
            'حقل' => 'Haql',
            'دومة الجندل' => 'Dumat Al Jandal',
            'سكاكا' => 'Sakaka',
            'ميقوع' => 'Mayqou',
            'ابو عجرم' => 'Abu Ajram',
            'صوير' => 'Suwayr',
            'النبك ابوقصر' => 'An Nabk Abu Qasr',
            'العويقيله' => 'Al Uwayqilah',
            'طبرجل' => 'Tabarjal',
            'الفياض' => 'Al Fayyad',
            'عرعر' => 'Arar',
            'القريات' => 'Al Qurayyat',
            'غطى' => 'Ghata',
            'طريف' => 'Turaif',
            'الشرقية' => 'Eastern Province',
            'الحدود الشمالية' => 'Northern Borders',
            'جازان' => 'Jazan',
            'الجوف' => 'Al Jouf',
            'عسير' => 'Asir',
            'مكة المكرمة' => 'Makkah', // Added missing translation for Makkah region
            'المدينة المنورة' => 'Madinah', // Added missing translation for Madinah region
            'القصيم' => 'Qassim', // Added missing translation for Qassim region
            'المنطقة الشرقية' => 'Eastern Province', // Added missing translation for Eastern Province region
            'خبت الخارش ومزارعة' => 'Khabt Al Kharish', // Added missing translation
        ];

        // Check if exact match exists
        if (array_key_exists($text, $translations)) {
            return $translations[$text];
        }

        return $text;
    }

    private function readCsv($path)
    {
        $data = [];
        if (($handle = fopen($path, 'r')) !== false) {
            // Handle BOM
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }

            $header = fgetcsv($handle);

            if (!$header) {
                fclose($handle);
                return [];
            }

            // Normalize headers: lowercase, remove special chars
            $header = array_map(function($h) {
                // Remove any non-alphanumeric characters except underscore
                $h = preg_replace('/[^a-z0-9_]/i', '', $h);
                return strtolower($h);
            }, $header);

            while (($row = fgetcsv($handle)) !== false) {
                // Skip empty rows or rows with mismatching column count
                if (count($row) !== count($header)) continue;

                $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }

    public function importPixels(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ], [
            'file.mimes' => 'Only CSV files are supported. Please convert Excel files to CSV (UTF-8) before uploading.'
        ]);

        try {
            $file = $request->file('file');
            $data = $this->readCsv($file->getRealPath());

            if (empty($data)) {
                return back()->with('error', 'The CSV file is empty or could not be read.');
            }

            DB::beginTransaction();

            $count = 0;
            foreach ($data as $row) {
                // Expected CSV headers: grid_id, region, city, lat, lon
                $gridId = $row['grid_id'] ?? $row['gridid'] ?? null;

                if (!$gridId) continue;

                // Try multiple variations for region/city keys
                $region = $row['region'] ?? $row['regionname'] ?? null;
                $city = $row['city'] ?? $row['cityname'] ?? $row['area'] ?? null;
                $lat = $row['lat'] ?? $row['latitude'] ?? $row['X'] ?? null;
                $lon = $row['lon'] ?? $row['longitude'] ?? $row['Y'] ?? $row['long'] ?? null;

                // Translate if Arabic
                $region = $this->translateArabicToEnglish($region);
                $city = $this->translateArabicToEnglish($city);

                $pixelData = [
                    'grid_id' => $gridId,
                    'region'  => $region,
                    'city'    => $city,
                    'lat'     => is_numeric($lat) ? $lat : null,
                    'lon'     => is_numeric($lon) ? $lon : null,
                ];

                Pixel::updateOrCreate(
                    ['grid_id' => $gridId],
                    $pixelData
                );
                $count++;
            }

            DB::commit();
            return back()->with('success', "$count Pixels imported successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Pixel Import Error: ' . $e->getMessage());
            return back()->with('error', 'Error importing pixels: ' . $e->getMessage());
        }
    }

    public function importRegions(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ], [
            'file.mimes' => 'Only CSV files are supported. Please convert Excel files to CSV (UTF-8) before uploading.'
        ]);

        try {
            $file = $request->file('file');
            $data = $this->readCsv($file->getRealPath());

            if (empty($data)) {
                return back()->with('error', 'The CSV file is empty or could not be read.');
            }

            DB::beginTransaction();

            $count = 0;
            foreach ($data as $row) {
                // Expected CSV headers: region, area, city_highway, test_type, lat, lon
                $region = $row['region'] ?? $row['regionname'] ?? null;
                $area = $row['area'] ?? $row['areaname'] ?? $row['city'] ?? null;

                if (!$region || !$area) continue;

                $city_highway = $row['city_highway'] ?? $row['cityhighway'] ?? null;
                $lat = $row['lat'] ?? $row['latitude'] ?? null;
                $lon = $row['lon'] ?? $row['longitude'] ?? $row['long'] ?? null;

                // Translate if Arabic
                $region = $this->translateArabicToEnglish($region);
                $area = $this->translateArabicToEnglish($area);
                $city_highway = $this->translateArabicToEnglish($city_highway);

                $regionData = [
                    'region'       => $region,
                    'area'         => $area,
                    'city_highway' => $city_highway,
                    'test_type'    => $row['test_type'] ?? $row['testtype'] ?? null,
                    'lat'          => is_numeric($lat) ? $lat : null,
                    'lon'          => is_numeric($lon) ? $lon : null,
                ];

                RegionCity::updateOrCreate(
                    [
                        'region' => $region,
                        'area'   => $area
                    ],
                    $regionData
                );
                $count++;
            }

            DB::commit();
            return back()->with('success', "$count Regions imported successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Region Import Error: ' . $e->getMessage());
            return back()->with('error', 'Error importing regions: ' . $e->getMessage());
        }
    }
}
