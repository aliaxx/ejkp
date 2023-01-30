<?php

use yii\helpers\Html;
use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use marekpetras\calendarview\CalendarView;

/**
 * Data displayed based on query
 * Calendar selected from TBTRANSGERAI.
 */

?>
<style>

    .content-table td {
        padding-bottom: 25px;
        vertical-align: top;
        font-size: 12px;
        /* font-family: Arial; */
    }

    .text{
        font-size: 13px;
        width: 500px;
        text-align:left;
        margin-left:7%;
    }

    .text1{
        font-size: 13px;
        width: 500px;
        text-align:left;
        margin-left:7%;
    }

    .text-border{
        font-size: 13px;
        width: 200px;
        text-align:left;
        border: 1px solid #000000;
        margin-left:7%;
    }

    .text-border1{
        font-size: 13px;
        width: 200px;
        text-align:left;
        border: 1px solid #000000;
        margin-left:-100px;
        margin-top:37px;
    }

    .border{
        width: 70%;
        border: 1px solid #000000;
    }

</style>



    <img style="width: 400px; height: 150px; margin-left:12%; margin-bottom: 30px;" <?= Html::img(Yii::getAlias('@web').'/images/mbpj_logo.png');?> >
    

    <p style="font-size: 15px; margin-top: 2px; margin-left:3%; width:100%;"><b>LAPORAN PEGERAI ADA BERNIAGA/TIDAK BERNIAGA LANGSUNG/SENDIRI/PPKP/PPKPK</b></p>

    <div style="padding-top:15px;text-align:justify;">

            <table class="content-table">
            <tr class="document-title">
                <td>
                    <div class="text"> Nama Pegerai : <?= $source["NAMAPEMOHON"] ?> </div>
                <div class="text"> Pasar/Gerai/Petak : <?= $source["LOCATION_NAME"] ?> </div>
                <div class="text-border"> &nbsp; No Gerai : <?= $source["NOPETAK"] ?> </div></td>
                <td><div class="text-border1"> &nbsp; Jualan : <?= $source["JENIS_JUALAN"] ?></div></td>
            </tr>
        </table>

        <table class="content-table">
            <tr style="padding-top:15px;">
                <td style="width: 210px;">&nbsp;</td>
                <td><u><div div class="text" colspan="3"> Bulan : <?= strtoupper($source["BULAN"]) ?></div></u></td>
            </tr>
        </table>


        <!-- calendar here -->
        <?=  $this->render('./laporan_pegerai-calendar', [
            'dataProvider' => $dataProvider,
        ]);
        ?>


        <table class="content-table">
            <tr class="document-title">
                <td style="padding-top:40px;text-align:left;width: 640px">
                    <div class="text" ><b> CATATAN :-</b>
                    <div class="text"> NAMA : <?= $source["NAMAPEKERJA"] ?></div>
                    <div class="text">NO. KP/PASSPORT: <?= $source["NOKPPEKERJA"] ?></div>
                    <div class="text">CATATAN: <?= $source["CATATAN"] ?></div>
                </td>
            </tr>
            <tr style="border: 1px solid #c2d6d6;">
                                <td style="text-align:left;">
                                    &nbsp; Petunjuk :- &nbsp;
                                    &nbsp; AB = AdaBerniaga &nbsp;
                                    &nbsp; TBS = TidakBerniagaSendiri &nbsp;
                                    &nbsp; TBL = TidakBerniagaLangsung&nbsp;
                                </td>
                            </tr>
        </table>


        <table class="content-table">
            <tr class="document-title">
                <td style="float:left; padding-top:40px; text-align:left;">
                    <h6>_______________________________________<h6>
                    <div class="text">(<?= $source["NAMA"] ?>)</div>
                    <div class="text">PEMBANTU KESIHATAN (AWAM)</div>
                    <div class="text">JABATAN PERKHIDMATAN KESIHATAN DAN PERSEKITARAN</div>
                    <div class="text">MAJLIS BANDARAYA PETALING JAYA</div>
                </td>

                <td style="float:right; padding-top:40px; padding-right: 20px;">
                    <div class="text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TARIKH: <?= date('d/m/Y') ?></div>
                </td>
            </tr>
        </table>

    
</div>
         