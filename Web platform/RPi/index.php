<?php
    if(isset($_GET["value"]) && isset($_GET["page"]))
    {
      $val = $_GET["value"];
      $page = $_GET["page"];
      $page += $val;
      if($page < 54) $page = 59;
      if($page > 59) $page = 54;
    }
    else
    {
      $page = 54;
    }
    $mysqli = new mysqli("localhost", "root", "", "PFE");
    $query = 'SELECT * FROM `SENSORS_STATIC` WHERE ID = '.$page.' LIMIT 1';
    $result = $mysqli->query($query);
    $rows = array();
    while($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }

    $result->free();
    $mysqli->close();
?>
<!DOCTYPE html>
<html>
  <head>
      <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
      <title>RPi - Data Logger v1.0</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
      <script src="dist/Chart.js"></script>
  </head>
  <body style="background-image:url(/assets/images/login.png); background-position: top; background-size: cover;">
    <div class="container" style="background: rgba(255,255,255,0.7);">
        <h1 class="page-header text-center">ESTS - Data Logger V1.0</h1>
        <div class="row">
            <div class="col-md-3">
                <h3 class="page-header text-center"><?php 
                            switch($page)
                            {
                              case 54:{
                                echo 'Courant DC';
                                break;
                              }
                              case 55:{
                                echo 'Courant AC';
                                break;
                              }
                              case 56:{
                                echo 'Tension DC';
                                break;
                              }
                              case 57:{
                                echo 'Température';
                                break;
                              }
                              case 58:{
                                echo 'Luminosité';
                                break;
                              }
                              case 59:{
                                echo 'Humidité';
                                break;
                              }
                            }
                          ?></h3>
                
                    <div class="form-group text-center">
                        <h4>Valeur en temps réel : <h1 class="text-center text-primary" id="updateVal"> 
                          <?php 
                            echo $rows[0]['VALUE'];
                            switch($page)
                            {
                              case 54:{
                                echo ' A';
                                break;
                              }
                              case 55:{
                                echo ' A';
                                break;
                              }
                              case 56:{
                                echo ' V';
                                break;
                              }
                              case 57:{
                                echo ' °C';
                                break;
                              }
                              case 58:{
                                echo ' %';
                                break;
                              }
                              case 59:{
                                echo ' %';
                                break;
                              }
                            }
                          ?>      
                          </h1></h4>
                    
                    <button type="submit" class="btn btn-primary" onclick= "location.href='index.php?value=-1&page=<?php echo $page; ?>'"><span class="glyphicon glyphicon-arrow-left"></span> Précédent</button>
                    <button type="submit" class="btn btn-success" onclick= "location.href='index.php?value=1&page=<?php echo $page; ?>'">Suivant <span class="glyphicon glyphicon-arrow-right"></span></button>
                    </div>
                
            </div>
            <div class="col-md-9">
                <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title"></h3>
                </div>
                <div class="box-body">
                  <div class="chart">
                    <canvas id="lineChart" style="height:250px"></canvas>
                  </div>
                </div>
            </div>
            </div>
        </div>
        <br>
      <footer class="footer text-center">
        © 2022 Data Logger by <a href="https://www.linkedin.com/in/yassine-bouelkheir/">BOUELKHEIR Yassine</a>
      </footer>
      <br>
    </div>
    <?php include('data.php'); ?>
    <script>
      $(function () {
        var lineChartData = {
          labels  : [<?php echo $tjan; ?>, <?php echo $tfeb; ?>, <?php echo $tmar; ?>, <?php echo $tapr; ?>, <?php echo $tmay; ?>, <?php echo $tjun; ?>, <?php echo $tjul; ?>, <?php echo $taug; ?>, <?php echo $tsep; ?>, <?php echo $toct; ?>],
          datasets: [
            {
              label               : '<?php 
                            switch($page)
                            {
                              case 54:{
                                echo 'Courant DC';
                                break;
                              }
                              case 55:{
                                echo 'Courant AC';
                                break;
                              }
                              case 56:{
                                echo 'Tension DC';
                                break;
                              }
                              case 57:{
                                echo 'Température';
                                break;
                              }
                              case 58:{
                                echo 'Luminosité';
                                break;
                              }
                              case 59:{
                                echo 'Humidité';
                                break;
                              }
                            }
                          ?>',
              borderColor: '#0000FF',
              data                : [ "<?php echo $pjan; ?>",
                                      "<?php echo $pfeb; ?>",
                                      "<?php echo $pmar; ?>",
                                      "<?php echo $papr; ?>",
                                      "<?php echo $pmay; ?>",
                                      "<?php echo $pjun; ?>",
                                      "<?php echo $pjul; ?>",
                                      "<?php echo $paug; ?>",
                                      "<?php echo $psep; ?>",
                                      "<?php echo $poct; ?>"
                                    ]
            },
          ]
        }
     
        var lineChartCanvas          = $('#lineChart').get(0).getContext('2d')
        var lineChartOptions = {
          showScale               : true,
          parseTime               : false,
          scaleShowGridLines      : false,
          scaleGridLineColor      : 'rgba(255,255,255,.05)',
          scaleGridLineWidth      : 1,
          scaleShowHorizontalLines: true,
          scaleShowVerticalLines  : true,
          bezierCurve             : true,
          bezierCurveTension      : 0.3,
          pointDot                : false,
          pointDotRadius          : 4,
          pointDotStrokeWidth     : 1,
          pointHitDetectionRadius : 20,
          datasetStroke           : true,
          datasetStrokeWidth      : 2,
          datasetFill             : true,
          legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
          maintainAspectRatio     : true,
          responsive              : true
        }
        lineChartOptions.datasetFill = false
        var lineChart = new Chart(lineChartCanvas,{
            type: 'line',
            data: lineChartData,
            options: lineChartOptions
        })
      })
    </script>
  </body>
</html>