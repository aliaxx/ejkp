<?php
use common\utilities\MenuHelper;

\backend\assets\SideNavAsset::register($this);
\backend\assets\JqueryCookieAsset::register($this);

$access = Yii::$app->access;

$this->registerJs("
    var sideMenu = new sideNav();
    function toggleSideNav() {
        sideMenu.toggle();
    }

    $(window).resize(function() {
        if ($(window).width() < 768) sideMenu.hide();
        else sideMenu.show();
    });

    $(window).on('load',function() {
        $('.sidenav').mCustomScrollbar({
            autoHideScrollbar:true,
            axis:'y',
        });
    });
", \yii\web\View::POS_END);
?>

<div class="sidenav no-print" style="position:fixed;top:54px;left:0;z-index:9;height:94vh;">
    <?php
//menubar untuk kawalan pengguna
        $menu['kawalan'] = [
            ['label' => 'Peranan Pengguna', 'url' => ['/peranan'],
                'active' => MenuHelper::isActive('peranan', 'controller'), 
                'visible' => $access->can('peranan-read')], //ini akan declare dekat /backend/components/Access.php
            ['label' => 'Profil Pengguna', 'url' => ['/user'],
                'active' => MenuHelper::isActive('user', 'controller'),
                'visible' => $access->can('pengguna-read')],
            ['label' => 'Subunit', 'url' => ['/penyelenggaraan/subunit/subunit'],
                'active' => MenuHelper::isActive('subunit', 'controller'),
                'visible' => $access->can('subunit-read')],
            ['label' => 'Log Audit', 'url' => ['/audit-log'],
                'active' => MenuHelper::isActive('audit-log', 'controller'),
                'visible' => $access->can('audit-log-read')],
        ];

        $menu['penyelenggaraan'] = [
        ['label' => 'Parameter Carian', 'items' => [
            ['label' => 'Carian Kumpulan', 'url' => ['/penyelenggaraan/param/param-header'],
                'active' => MenuHelper::isActive('param-header', 'controller'),
                'visible' => $access->can('param-header-read')],
            ['label' => 'Carian Terperinci', 'url' => ['/penyelenggaraan/param/param-detail'],
                'active' => MenuHelper::isActive('param-detail', 'controller'),
                'visible' => $access->can('param-detail-read')],
        ]],
        ['label' => 'Parameter Penggredan Premis/Tandas', 'items' => [
            ['label' => 'Perkara', 'url' => ['/penyelenggaraan/penggredan/perkara'],
                'active' => MenuHelper::isActive('penyelenggaraan/penggredan/perkara'),
                'visible' => $access->can('perkara-read')],
            ['label' => 'Komponen', 'url' => ['/penyelenggaraan/penggredan/perkara-komponen'],
                'active' => MenuHelper::isActive('penyelenggaraan/penggredan/perkara-komponen'),
                'visible' => $access->can('komponen-read')],
            ['label' => 'Keterangan', 'url' => ['/penyelenggaraan/penggredan/perkara-komponen-prgn'],
                'active' => MenuHelper::isActive('penyelenggaraan/penggredan/perkara-komponen-prgn'),
                'visible' => $access->can('keterangan-read')],
        ]],
        // ['label' => 'Perundangan', 'items' => [
        //     ['label' => 'Akta', 'url' => ['/penyelenggaraan/perundangan/akta'],
        //         'active' => MenuHelper::isActive('penyelenggaraan/perundangan/akta'),
        //         'visible' => $access->can('akta-read')],
        //     ['label' => 'Seksyen Kesalahan', 'url' => ['/penyelenggaraan/perundangan/seksyen-kesalahan'],
        //         'active' => MenuHelper::isActive('penyelenggaraan/perundangan/seksyen-kesalahan'),
        //         'visible' => $access->can('seksyen-read')],
        // ]],
        ['label' => 'Dun', 'url' => ['/penyelenggaraan/dun/dun'],
            'active' => MenuHelper::isActive('penyelenggaraan/dun/dun'),
            'visible' => $access->can('dun-read')],
        ['label' => 'Lokaliti', 'url' => ['/penyelenggaraan/lokaliti/lokaliti'],
            'active' => MenuHelper::isActive('penyelenggaraan/lokaliti/lokaliti'),
            'visible' => $access->can('lokaliti-read')],
        ['label' => 'Parameter Bacaan Air Kolam', 'url' => ['/penyelenggaraan/kolam/bacaan-kolam'],//controller
            'active' => MenuHelper::isActive('penyelenggaraan/kolam/bacaan-kolam'),// path folder
            'visible' => $access->can('kolam-read')], //declare at access.php
      ];

//menu untuk integrasi
      $menu['integrasi'] = [ //path 
        ['label' => 'Integrasi Lesen', 'url' => ['/integrasi/lesen'],
            'active' => MenuHelper::isActive('integrasi/lesen'),
            'visible' => $access->can('lesen-read')],
            ['label' => 'Integrasi Sewa', 'url' => ['/integrasi/sewa'],
            'active' => MenuHelper::isActive('integrasi/sewa'),
            'visible' => $access->can('sewa-read')],
      ];

//menu untuk kawalan mutu makanan
       $menu['makanan'] = [ //path 
            ['label' => 'Sampel Makanan', 'url' => ['/makanan/sampel'],
                'active' => MenuHelper::isActive('makanan/sampel'),
                'visible' => $access->can('SMM-read')],
            ['label' => 'Sitaan', 'url' => ['/makanan/sitaan'],
                'active' => MenuHelper::isActive('makanan/sitaan'),
                'visible' => $access->can('SDR-read')], 
            ['label' => 'Handswab', 'url' => ['/makanan/handswab'],
                'active' => MenuHelper::isActive('makanan/handswab'),
                'visible' => $access->can('HSW-read')], 
            ['label' => 'Pemeriksaan Kolam', 'url' => ['/makanan/kolam'],
                'active' => MenuHelper::isActive('makanan/kolam'),
                'visible' => $access->can('PKK-read')], 
            ];            

//menu untuk kawalan pencegahan vektor
        $menu['vektor'] = [
            ['label' => 'Semburan Termal(SRT)', 'url' => ['/vektor/srt'],
                'active' => MenuHelper::isActive('vektor/srt'),
                'visible' => $access->can('SRT-read')], 
            ['label' => 'Semburan Kabus Mesin(ULV)', 'url' => ['/vektor/ulv'],
                'active' => MenuHelper::isActive('vektor/ulv'),
                'visible' => $access->can('ULV-read')], 
            ['label' => 'Pemeriksaan Tempat Pembiakan Aedes(PTP)', 'url' => ['/vektor/ptp'],
                'active' => MenuHelper::isActive('vektor/ptp'),
                'visible' => $access->can('PTP-read')],
            ['label' => 'Aktiviti Larvaciding', 'url' => ['/vektor/lvc'],
                'active' => MenuHelper::isActive('vektor/lvc'),
                'visible' => $access->can('LVC-read')],
            ['label' => 'Pemeriksaan Tandas', 'url' => ['/vektor/tandas'],
                'active' => MenuHelper::isActive('vektor/tandas'),
                'visible' => $access->can('PTS-read')],
        ];
           
        //menu untuk peniaga kecil dan penjaja
        $menu['peniaga'] = [
            ['label' => 'Pemantauan Penjaja', 'url' => ['/peniaga/kawalan-perniagaan'],
                'active' => MenuHelper::isActive('peniaga/kawalan-perniagaan'),
                'visible' => $access->can('KPN-read')],
        ];

//menu untuk penggredan premis makanan
        $menu['premis'] = [
            ['label' => 'Pemeriksaan Premis', 'url' => ['/premis/penggredan-premis'],
                'active' => MenuHelper::isActive('premis/penggredan-premis'), 
                'visible' => $access->can('PPM-read')],
            ['label' => 'Anugerah Premis Bersih', 'url' => ['/premis/anugerah'],
                'active' => MenuHelper::isActive('premis/anugerah'),
                'visible' => $access->can('APB-read')],
        ];

        $menu['laporan'] = [
            ['label' => 'Mutu Makanan', 'url' => ['/laporan/mutu-makanan'],
                'active' => MenuHelper::isActive('laporan'),
                'visible' => $access->can('laporan-mutu-makanan-read')],//laporan kena sama dekat access&sidenav.
            ['label' => 'Pencegahan Vektor', 'url' => ['/laporan/vektor'],
                'active' => MenuHelper::isActive('laporan/vektor'),
                'visible' => $access->can('laporan-vektor-read')],
            ['label' => 'Peniaga Kecil & Penjaja', 'url' => ['/laporan/kawalan-perniagaan'],
                'active' => MenuHelper::isActive('laporan'),
                'visible' => $access->can('laporan-penjaja-read')],
            ['label' => 'Premis Makanan', 'url' => ['/laporan/premis-makanan'],
                'active' => MenuHelper::isActive('laporan'),
                'visible' => $access->can('laporan-premis-makanan-read')],
        ];
       
        // Menu based on access level
        // If peranan is null, the menu will display dash & laporan only because of menuhelper::isactive. So insert that to another menu.
        $menuItems[] = ['label' => 'Dashboard', 'icon' => 'tachometer-alt', 'url' => ['/site/dashboard'],
            'active' => MenuHelper::isActive('site/dashboard')];
        $menuItems[] = ['label' => 'Kawalan Pengguna', 'icon' => 'users', 'items' => $menu['kawalan']];
        $menuItems[] = ['label' => 'Penyelenggaraan', 'icon' => 'cog', 'items' => $menu['penyelenggaraan']];
        $menuItems[] = ['label' => 'Integrasi', 'icon' => 'laptop', 'items' => $menu['integrasi']];
        $menuItems[] = ['label' => 'Mutu Makanan', 'icon' => 'utensils', 'items' => $menu['makanan']];
        $menuItems[] = ['label' => 'Pencegahan Vektor', 'icon' => 'spray-can', 'items' => $menu['vektor']];
        $menuItems[] = ['label' => 'Peniaga Kecil & Penjaja', 'icon' => 'store', 'items' => $menu['peniaga']];
        $menuItems[] = ['label' => 'Premis Makanan', 'icon' => 'warehouse',  'items' => $menu['premis']];
        $menuItems[] = ['label' => 'Laporan', 'icon' => 'table', 'items' => $menu['laporan']];
        
        //$menuItems[] = ['label' => 'Parameter', 'icon' => 'users', 'items' => $menu['param']];
        
    ?>

    <?= \kartik\sidenav\SideNav::widget([
        'type' => \kartik\sidenav\SideNav::TYPE_DEFAULT,
        'heading' => false,
        'iconPrefix' => 'fa fa-',
        'indItem' => '',
        'encodeLabels' => false,
        'items' => $menuItems,
    ]) ?>
</div>
