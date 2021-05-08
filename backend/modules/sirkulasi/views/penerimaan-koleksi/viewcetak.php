

<div class="panel panel-default panel-body" style="font-family: times new roman; border: none; padding:40px; margin-top:0px;" >

  <center style="text-align: center; font-weight: bold;">
    <p style="text-align: center; font-size: 14px">
      <?=$judulCetak?>
      
      
    </p>
  </center>

  <center style="font-size: 11px;"> 
    <table width="100%" border="1" class="table table-bordered" style="border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
          <thead>
            <tr>
              <th>No</th>
              <th>Nomor Barcode</th>
              <th>Nomor Induk</th>
              <th style="text-align: center;">Judul</th>
              <th>Tahun Terbit</th>
            </tr>
          </thead>
          <tbody>
               <?php
                $i = 1;
                $totalJumlahExemplar = 0;
                foreach ($model as $row) {
                    # code...
                ?>
                    <tr>
                        <td>
                            <?=$i++?>
                        </td>
                        <td>
                            <?=$row['NOBARCODE']?>
                        </td>
                        <td>
                            <?=$row['NOINDUK']?>
                        </td>
                        <td >
                            <?=$row['JUDUL']?>
                        </td>
                        <td >
                            <?=$row['TAHUNTERBIT']?>
                        </td>
                        
                    </tr>

                    
                <?php
                    $totalJumlahExemplar = $totalJumlahExemplar + $row['QUANTITY'];
                }
                
                ?>
                
                
              </tbody>
          

        </table>

        <br>
        <p style="text-align: right; font-size: 15px;">............................. , <?= date('d-F-Y', strtotime(date('Y-m-d'))) ?></p>
      
        
        


      <div class="row" style="text-align: center;">
          <!-- <center> -->
              <!-- accepted payments column -->
              <div class="col-xs-2">
                <h5>Penerima</h5>
                
                <br/><br/><br/><br/> <b>( . . . . . . . )</b>
              </div><!-- /.col -->
              <div class="col-xs-4">
               <h5>Mengetahui</h5>
               <br/><br/><br/><br/> <b>( <?=$penanggungjawab?> )</b> 
               </div>

               <div class="col-xs-4">
               <h5>Pengirim</h5>
               <br/><br/><br/><br/> <b>( . . . . . . . )</b> 
               </div>
            <!-- </center> -->
          </div><!-- /.col -->
  </center>

  
</div>
