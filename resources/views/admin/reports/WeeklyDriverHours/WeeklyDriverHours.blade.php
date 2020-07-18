<html>
    <head>
        <style>

@page {
                margin: 0cm 0cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 5cm;
                margin-left: 2cm;
                margin-right: 2cm;
                margin-bottom: 2cm;
            }

            /** Define the header rules **/
            header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 11cm;
            }

            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 3cm;
            }

            .pagenum:before {
                content: counter(page);
            }
            .footer {
                text-align: center;
                position: fixed;

                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 1cm;
            }

        table {
        width: 100%;
        border-collapse: collapse;

        }

        th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
        color: #fff;
        background-color: #343a40;
        border-color: #454d55;
        color: white;
        padding: 10px;
        }
        td {    
        border: 1px solid #dee2e6;
        vertical-align: center;
        }
        .table-primary{
            background-color: #b8daff;
        }
        </style>
    </head>
    <body>
            <header>
                    <center>
                        <h1>
                            <strong>
                              Chilliwack Taxi 
                            </strong>
                            : Weekly driver hours
                        </h1>
                        <h3>
                            Driver: {{$name}}<br>
                            Week: {{$date}} 
                            
                        </h3>
                    </center>
                    <br>
                    <br>
            </header>
            
            <div class="footer">
                Page <span class="pagenum"></span>
            </div>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Driver</th>
                    <th scope="col">Taxi Number</th>
                    <th scope="col">Start Time</th>
                    <th scope="col">End Time</th>
                    <th scope="col">Total Time</th>
                    <th scope="col">Break Time</th>
                    <th scope="col">Work Time</th>
                </tr>
            </thead>
            <tbody>
                                @foreach ($shifts as $shift)
                                @php
                                    echo '<tr>';
                                        echo '<td>'.$shift['FirstName'].' '.$shift['LastName'].'</td>';
                                        echo '<td>'.$shift['vehicle_id'].'</td>';
                                        echo '<td>'.$shift['shift_start_time'].'</td>';
                                        echo '<td>'.$shift['shift_end_time'].'</td>';
                                        echo '<td>'.$shift['total_time'].'</td>';
                                        echo '<td>'.$shift['break_time'].'</td>';
                                        echo '<td>'.$shift['work_time'].'</td>';
                                        echo '</tr>';
                                @endphp
                        @endforeach
                        @php
                            echo '<tr class="table-primary  ">';
                                echo '<td colspan="4" class ="text-uppercase">'.$shifts[0]['FirstName'].' '.$shifts[0]['LastName'].' TOTAL '.'</td>';

                                if($total_row['total_time_hours']==0 && $total_row['total_time_min'] ==0)
                                {
                                    echo '<td>-</td>';
                                }
                                else {
                                    echo '<td>'.$total_row['total_time_hours'].'H: '.$total_row['total_time_min']."Min".'</td>';
                                }

                                if($total_row['break_time_hours']==0 && $total_row['break_time_min'] ==0)
                                {
                                    echo '<td>-</td>';
                                }
                                else {
                                    echo '<td>'.$total_row['break_time_hours']."H: ".$total_row['break_time_min']."Min".'</td>';
                                }

                                if($total_row['work_time_hours']==0 && $total_row['work_time_min'] ==0)
                                {
                                    echo '<td>-</td>';
                                }
                                else {
                                    echo '<td>'.$total_row['work_time_hours']."H: ".$total_row['work_time_min']."Min".'</td>';
                                }
                            echo '</tr>';
                        @endphp
            </tbody>
    </table>
    
</body>
</html>