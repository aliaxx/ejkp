<?php

use common\models\Pengguna;
use common\utilities\DateTimeHelper;
use yii\db\ActiveQuery;
use backend\modules\peniaga\models\Transgerai;
use yii\helpers\Url;
use yii\helpers\Html;
// use backend\modules\kompaun\utilities\OptionHandler;

/**
 * @var Transgerai $model
 */

$alphabet = [
    'a', 'b', 'c', 'd', 'e',
    'f', 'g', 'h', 'i', 'j',
    'k', 'l', 'm', 'n', 'o',
    'p', 'q', 'r', 's', 't',
    'u', 'v', 'w', 'x', 'y',
    'z'
];
?>
<style>
    .content-table td {
        padding-bottom: 25px;
        vertical-align: top;
        font-size: 12px;
        /* font-family: Arial; */
    }

    .text{
        font-size: 15px;
        width: 240px;
        text-align:left;
    }

    .text-border{
        font-size: 15px;
        width: 240px;
        text-align:left;
        border: 1px solid #000000;
    }
</style>

<img src="ejkp/backend/web/images/logo.png">


<h4 style="text-align: center; margin-top: 10px;"><b>LAPORAN PEGERAI ADA BERNIAGA/TIDAK BERNIAGA LANGSUNG/SENDIRI/PPKP/PPKPK</b></h4>

    <!-- Maklumat Pegerai -->
    <div style="padding-top:15px;text-align:justify;">
        <table class="content-table">
            <tr style="padding-top:15px;">
                <td><div class="text"> Nama Pegawai : </td>
            </tr>
            <tr style="padding-top:15px;">
                <td><div class="text"> Pasar/Gerai/Petak : </div></td>
            </tr>
            <tr style="padding-top:15px;">
                <td><div class="text-border"> &nbsp; No Gerai : </div></td>
                <td style="width: 350px;">&nbsp;</td>
                <td><div class="text-border"> &nbsp; Jualan : </div></td>
            </tr>
        </table>

        <table class="content-table">
            <tr style="padding-top:15px;">
                <td style="width: 180px;">&nbsp;</td>
                <td><u><div div class="text" colspan="3"> Bulan : </div></u></td>
            </tr>
        </table>


    <!-- calendar here -->


        <!-- Catatan -->
        <table class="content-table">
            <tr class="document-title">
                <td style="padding-top:40px;text-align:left;width: 640px">
                    <div class="text" ><b> CATATAN</b>
                    <div class="text"> NAMA : </div>
                    <div class="text">NO. KP/PASSPORT: </div>
                    <div class="text">HUBUNGAN: </div>
                    <div class="text">LAIN-LAIN: </div>
                </td>
            </tr>
        </table>


        <!-- Signature -->
        <table class="content-table">
            <tr class="document-title">
                <td style="float:left; padding-top:40px; text-align:left;">
                    <h6>____________________________________________<h6>
                    <div class="text">(                             )</div>
                    <div class="text">PEMBANTU KESIHATAN (AWAM)</div>
                    <div class="text">JABATAN PERKHIDMATAN KESIHATAN DAN PERSEKITARAN</div>
                    <div class="text">MAJLIS BANDARAYA PETALING JAYA</div>
                </td>

                <td style="float:right; padding-top:40px; padding-right: 50px;">
                    <div class="text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TARIKH:</div>
                    <br>
                        <table style="margin-left: 25px;">
                            <tr style="border: 1px solid #c2d6d6;">
                                <td style="text-align:left;">
                                <br>
                                    &nbsp; Petunjuk :- &nbsp;<br>
                                    &nbsp; AB = AdaBerniaga &nbsp;<br>
                                    &nbsp; TBS = TidakBerniagaSendiri &nbsp;<br>
                                    &nbsp; TBL = TidakBerniagaLangsung&nbsp;
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

    </div>
         