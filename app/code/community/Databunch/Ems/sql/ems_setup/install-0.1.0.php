<?php
/* @var $this Mage_Core_Model_Resource_Setup */
$this->startSetup();
/** @var Varien_Db_Adapter_Pdo_Mysql $connection */
$connection = $this->getConnection();

$regions = array(
    array("Республика Адыгея", "AD", "respublika-adygeja"),
    array("Республика Башкортостан", "BA", "respublika-bashkortostan"),
    array("Республика Бурятия", "BU", "respublika-burjatija"),
    array("Республика Алтай", "AL", "respublika-altaj"),
    array("Республика Дагестан", "DA", "respublika-dagestan"),
    array("Республика Ингушетия", "IN", "respublika-ingushetija"),
    array("Кабардино-Балкарская республика", "KB", "kabardino-balkarskaja-respublika"),
    array("Республика Калмыкия", "KL", "respublika-kalmykija"),
    array("Карачаево-Черкесская республика", "KC", "karachaevo-cherkesskaja-respublika"),
    array("Республика Карелия", "KR", "respublika-karelija"),
    array("Республика Коми", "KO", "respublika-komi"),
    array("Республика Марий Эл", "ME", "respublika-marij-el"),
    array("Республика Мордовия", "MO", "respublika-mordovija"),
    array("Республика Саха (Якутия)", "SA", "respublika-saha-yakutija"),
    array("Республика Северная Осетия — Алания", "SE", "respublika-sev.osetija-alanija"),
    array("Республика Татарстан", "TA", "respublika-tatarstan"),
    array("Республика Тыва", "TY", "respublika-tyva"),
    array("Удмуртская республика", "UD", "udmurtskaja-respublika"),
    array("Республика Хакасия", "KK", "respublika-khakasija"),
    array("Чеченская республика", "CE", "chechenskaya-respublika"),
    array("Чувашская республика", "CU", "chuvashskaja-respublika"),
    array("Алтайский край", "ALT", "altajskij-kraj"),
    array("Забайкальский край", "ZAB", "zabajkalskij-kraj"),
    array("Камчатский край", "KAM", "kamchatskij-kraj"),
    array("Краснодарский край", "KDA", "krasnodarskij-kraj"),
    array("Красноярский край", "KYA", "krasnojarskij-kraj"),
    array("Пермский край", "PER", "permskij-kraj"),
    array("Приморский край", "PRI", "primorskij-kraj"),
    array("Ставропольский край", "STA", "stavropolskij-kraj"),
    array("Хабаровский край", "KHA", "khabarovskij-kraj"),
    array("Амурская область", "AMU", "amurskaja-oblast"),
    array("Архангельская область", "ARK", "arhangelskaja-oblast"),
    array("Астраханская область", "AST", "astrahanskaja-oblast"),
    array("Белгородская область", "BEL", "belgorodskaja-oblast"),
    array("Брянская область", "BRY", "brjanskaja-oblast"),
    array("Владимирская область", "VLA", "vladimirskaja-oblast"),
    array("Волгоградская область", "VGG", "volgogradskaja-oblast"),
    array("Вологодская область", "VLG", "vologodskaja-oblast"),
    array("Воронежская область", "VOR", "voronezhskaja-oblast"),
    array("Ивановская область", "IVA", "ivanovskaja-oblast"),
    array("Иркутская область", "IRK", "irkutskaja-oblast"),
    array("Калининградская область", "KGD", "kaliningradskaja-oblast"),
    array("Калужская область", "KLU", "kaluzhskaja-oblast"),
    array("Кемеровская область", "KEM", "kemerovskaja-oblast"),
    array("Кировская область", "KIR", "kirovskaja-oblast"),
    array("Костромская область", "KOS", "kostromskaja-oblast"),
    array("Курганская область", "KGN", "kurganskaja-oblast"),
    array("Курская область", "KRS", "kurskaja-oblast"),
    array("Ленинградская область", "LEN", "leningradskaja-oblast"),
    array("Липецкая область", "LIP", "lipeckaja-oblast"),
    array("Магаданская область", "MAG", "magadanskaja-oblast"),
    array("Московская область", "MOS", "moskovskaja-oblast"),
    array("Мурманская область", "MUR", "murmanskaja-oblast"),
    array("Нижегородская область", "NIZ", "nizhegorodskaja-oblast"),
    array("Новгородская область", "NGR", "novgorodskaja-oblast"),
    array("Новосибирская область", "NVS", "novosibirskaja-oblast"),
    array("Омская область", "OMS", "omskaja-oblast"),
    array("Оренбургская область", "ORE", "orenburgskaja-oblast"),
    array("Орловская область", "ORL", "orlovskaja-oblast"),
    array("Пензенская область", "PNZ", "penzenskaja-oblast"),
    array("Псковская область", "PSK", "pskovskaja-oblast"),
    array("Ростовская область", "ROS", "rostovskaja-oblast"),
    array("Рязанская область", "RYA", "rjazanskaja-oblast"),
    array("Самарская область", "SAM", "samarskaja-oblast"),
    array("Саратовская область", "SAR", "saratovskaja-oblast"),
    array("Сахалинская область", "SAK", "sahalinskaja-oblast"),
    array("Свердловская область", "SVE", "sverdlovskaja-oblast"),
    array("Смоленская область", "SMO", "smolenskaja-oblast"),
    array("Тамбовская область", "TAM", "tambovskaja-oblast"),
    array("Тверская область", "TVE", "tverskaja-oblast"),
    array("Томская область", "TOM", "tomskaja-oblast"),
    array("Тульская область", "TUL", "tulskaja-oblast"),
    array("Тюменская область", "TYU", "tjumenskaja-oblast"),
    array("Ульяновская область", "ULY", "uljanovskaja-oblast"),
    array("Челябинская область", "CHE", "cheljabinskaja-oblast"),
    array("Ярославская область", "YAR", "yaroslavskaja-oblast"),
    array("Еврейская автономная область", "YEV", "evrejskaja-ao"),
    array("Ненецкий автономный округ", "NEN", "neneckij-ao"),
    array("Ханты-Мансийский автономный округ - Югра", "KHM", "khanty-mansijskij-ao"),
    array("Чукотский автономный округ", "CHU", "chukotskij-ao"),
    array("Ямало-Ненецкий автономный округ", "YAN", "yamalo-neneckij-ao"),
);

$cities = array(
    array("abakan","АБАКАН"),
    array("anadyr","АНАДЫРЬ"),
    array("anapa","АНАПА"),
    array("arhangelsk","АРХАНГЕЛЬСК"),
    array("astrahan","АСТРАХАНЬ"),
    array("bajkonur","БАЙКОНУР"),
    array("barnaul","БАРНАУЛ"),
    array("belgorod","БЕЛГОРОД"),
    array("birobidzhan","БИРОБИДЖАН"),
    array("blagoveshhensk","БЛАГОВЕЩЕНСК"),
    array("brjansk","БРЯНСК"),
    array("velikij-novgorod","ВЕЛИКИЙ НОВГОРОД"),
    array("vladivostok","ВЛАДИВОСТОК"),
    array("vladikavkaz","ВЛАДИКАВКАЗ"),
    array("vladimir","ВЛАДИМИР"),
    array("volgograd","ВОЛГОГРАД"),
    array("vologda","ВОЛОГДА"),
    array("vorkuta","ВОРКУТА"),
    array("voronezh","ВОРОНЕЖ"),
    array("gorno-altajsk","ГОРНО-АЛТАЙСК"),
    array("groznyj","ГРОЗНЫЙ"),
    array("dudinka","ДУДИНКА"),
    array("ekaterinburg","ЕКАТЕРИНБУРГ"),
    array("elizovo","ЕЛИЗОВО"),
    array("ivanovo","ИВАНОВО"),
    array("izhevsk","ИЖЕВСК"),
    array("irkutsk","ИРКУТСК"),
    array("ioshkar-ola","ЙОШКАР-ОЛА"),
    array("kazan","КАЗАНЬ"),
    array("kaliningrad","КАЛИНИНГРАД"),
    array("kaluga","КАЛУГА"),
    array("kemerovo","КЕМЕРОВО"),
    array("kirov","КИРОВ"),
    array("kostomuksha","КОСТОМУКША"),
    array("kostroma","КОСТРОМА"),
    array("krasnodar","КРАСНОДАР"),
    array("krasnojarsk","КРАСНОЯРСК"),
    array("kurgan","КУРГАН"),
    array("kursk","КУРСК"),
    array("kyzyl","КЫЗЫЛ"),
    array("lipeck","ЛИПЕЦК"),
    array("magadan","МАГАДАН"),
    array("magnitogorsk","МАГНИТОГОРСК"),
    array("majkop","МАЙКОП"),
    array("mahachkala","МАХАЧКАЛА"),
    array("mineralnye-vody","МИНЕРАЛЬНЫЕ ВОДЫ"),
    array("mirnyj","МИРНЫЙ"),
    array("moskva","МОСКВА"),
    array("murmansk","МУРМАНСК"),
    array("mytishhi","МЫТИЩИ"),
    array("naberezhnye-chelny","НАБЕРЕЖНЫЕ ЧЕЛНЫ"),
    array("nadym","НАДЫМ"),
    array("nazran","НАЗРАНЬ"),
    array("nalchik","НАЛЬЧИК"),
    array("narjan-mar","НАРЬЯН-МАР"),
    array("nerjungri","НЕРЮНГРИ"),
    array("neftejugansk","НЕФТЕЮГАНСК"),
    array("nizhnevartovsk","НИЖНЕВАРТОВСК"),
    array("nizhnij-novgorod","НИЖНИЙ НОВГОРОД"),
    array("novokuzneck","НОВОКУЗНЕЦК"),
    array("novorossijsk","НОВОРОССИЙСК"),
    array("novosibirsk","НОВОСИБИРСК"),
    array("novyj-urengoj","НОВЫЙ УРЕНГОЙ"),
    array("norilsk","НОРИЛЬСК"),
    array("nojabrsk","НОЯБРЬСК"),
    array("omsk","ОМСК"),
    array("orel","ОРЁЛ"),
    array("orenburg","ОРЕНБУРГ"),
    array("penza","ПЕНЗА"),
    array("perm","ПЕРМЬ"),
    array("petrozavodsk","ПЕТРОЗАВОДСК"),
    array("petropavlovsk-kamchatskij","ПЕТРОПАВЛОВСК-КАМЧАТСКИЙ"),
    array("pskov","ПСКОВ"),
    array("rostov-na-donu","РОСТОВ-НА-ДОНУ"),
    array("rjazan","РЯЗАНЬ"),
    array("salehard","САЛЕХАРД"),
    array("samara","САМАРА"),
    array("sankt-peterburg","САНКТ-ПЕТЕРБУРГ"),
    array("saransk","САРАНСК"),
    array("saratov","САРАТОВ"),
    array("smolensk","СМОЛЕНСК"),
    array("sochi","СОЧИ"),
    array("stavropol","СТАВРОПОЛЬ"),
    array("strezhevoj","СТРЕЖЕВОЙ"),
    array("surgut","СУРГУТ"),
    array("syktyvkar","СЫКТЫВКАР"),
    array("tambov","ТАМБОВ"),
    array("tver","ТВЕРЬ"),
    array("toljatti","ТОЛЬЯТТИ"),
    array("tomsk","ТОМСК"),
    array("tula","ТУЛА"),
    array("tynda","ТЫНДА"),
    array("tjumen","ТЮМЕНЬ"),
    array("ulan-udje","УЛАН-УДЭ"),
    array("uljanovsk","УЛЬЯНОВСК"),
    array("usinsk","УСИНСК"),
    array("ufa","УФА"),
    array("uhta","УХТА"),
    array("khabarovsk","ХАБАРОВСК"),
    array("khanty-mansijsk","ХАНТЫ-МАНСИЙСК"),
    array("kholmsk","ХОЛМСК"),
    array("cheboksary","ЧЕБОКСАРЫ"),
    array("cheljabinsk","ЧЕЛЯБИНСК"),
    array("cherepovec","ЧЕРЕПОВЕЦ"),
    array("cherkessk","ЧЕРКЕССК"),
    array("chita","ЧИТА"),
    array("elista","ЭЛИСТА"),
    array("yuzhno-sahalinsk","ЮЖНО-САХАЛИНСК"),
    array("yakutsk","ЯКУТСК"),
    array("yaroslavl","ЯРОСЛАВЛЬ"),
    array("sevastopol","СЕВАСТОПОЛЬ"),
    array("simferopol","СИМФЕРОПОЛЬ"),
);

$regionTableName    = $this->getTable('directory/country_region');
$emsRegionTableName = $this->getTable('ems/ems_region');
$table = $connection->newTable($emsRegionTableName);

$table->addColumn('region_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
    'nullable'  => false,
    'identity'  => false,
    'unsigned'  => true,
    'primary'   => true,
), 'Region Id');

$table->addColumn('ems_code', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array(
    'nullable'  => false,
    'identity'  => false,
    'unsigned'  => true,
    'primary'   => true,
), 'Region Id');

$table->addColumn('ems_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array(
    'nullable'  => false,
    'identity'  => false,
    'unsigned'  => true,
    'primary'   => true,
), 'Type');

$table->addForeignKey(
    $this->getFkName('ems/ems_region', 'region_id', 'directory/country_region', 'region_id'),
    'region_id', $this->getTable('directory/country_region'), 'region_id',
    Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_NO_ACTION);

$connection->createTable($table);

$connection->beginTransaction();
try {
    $connection->delete($regionTableName, 'country_id = "RU"');

    foreach ($regions as $regionData) {
        $name    = $regionData[0];
        $isoCode = $regionData[1];
        $emsCode = $regionData[2];

        $connection->insert($regionTableName, array(
            'country_id' => 'RU',
            'code'         => $isoCode,
            'default_name' => $name,
        ));

        $connection->insert($emsRegionTableName, array(
            'region_id' => $connection->lastInsertId(),
            'ems_code'  => $emsCode,
            'ems_type'  => 'region'
        ));

        $connection->lastInsertId();
    }
    $connection->commit();
} catch (Exception $e) {
    $connection->rollBack();
    throw $e;
}
$connection->beginTransaction();
try {
    foreach ($cities as $cityData) {
        $name    = $cityData[1];
        $emsCode = $cityData[0];

        $connection->insert($regionTableName, array(
            'country_id' => 'RU',
            'code'         => $emsCode,
            'default_name' => $name,
        ));

        $connection->insert($emsRegionTableName, array(
            'region_id' => $connection->lastInsertId(),
            'ems_code'  => $emsCode,
            'ems_type'  => 'city'
        ));

        $connection->lastInsertId();
    }
    $connection->commit();
} catch (Exception $e) {
    $connection->rollBack();
    throw $e;
}
$this->endSetup();