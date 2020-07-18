<html>
    <head>
        <style>
        @include('admin.inc.pdf_css_table')
        @page {
                margin: 0cm 0cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 3cm;
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

        </style>
    </head>
    <body>

            <header>
                <center>
                    <h1>
                        <strong>
                          Chilliwack Taxi 
                        </strong>
                        : Daily Log Sheet report
                    </h1>
                    <h3>
                            Driver: {{$dailyLogSheet['name']}} - Month: {{$dailyLogSheet['date']}}
                    </h3>
                </center>
                <br>
                <br>
        </header>
        
        <div class="footer">
            Page <span class="pagenum"></span>
        </div>


            <div class="footer">
                Page <span class="pagenum"></span>
            </div>






            <!-- table -->
    <div class="table-responsive-lg">
      <table class="table table-bordered table-hover">
        <thead class="thead-dark">
          <tr>
            <th class="align-middle" scope="col">JOBID</th>
            <th class="align-middle" scope="col">LOAD TIME</th>
            <th class="align-middle" scope="col">UNLOAD TIME</th>
            <th class="align-middle" scope="col">CUSTOMER</th>
            <th class="align-middle" scope="col">PICK UP</th>
            <th class="align-middle" scope="col">DROP OFF</th>
            <th class="align-middle" scope="col">FARE</th>
            <th class="align-middle" scope="col">TIP</th>
            <th class="align-middle" scope="col">TOTAL</th>
            <th class="align-middle" scope="col">TRIP TYPE</th>
            <th class="align-middle" scope="col">ACCOUNT NAME</th>
            <th class="align-middle" scope="col">ACCOUNT NUMBER</th>
            <th class="align-middle" scope="col">PO</th>
          </tr>
        </thead>
        <tbody>
        /* for each */
        @foreach ($dailyLogSheet['trips'] as $trip)
          <tr>
            <td>{{$trip->job_id}}</td>
            <td>{{$trip->pickup_date}}</td>
            <td>{{$trip->dropoff__date}}</td>
            <td>{{$trip->customer_name}}</td>
            <td>{{$trip->pickup}}</td>
            <td>{{$trip->dropoff}}</td>
            <td>{{$trip->fare}}</td>
            <td>{{$trip->tip}}</td>
            <td>{{$trip->total}}</td>
            <td>{{$trip->account}}</td>
            <td></td>
            <td></td>
            <td>{{$trip->po}}</td>
          </tr>
          @endforeach 

          /* end for each */
          <tr class="table table-borderless">
            <td colspan="6" class="table-success" style="font-size: 1.2em;">Total</td>
            <td class="table-primary font-weight-bold">${{$dailyLogSheet['total_fare']}}</td>
            <td class="table-primary font-weight-bold">${{$dailyLogSheet['total_tip']}}</td>
            <td class="table-primary font-weight-bold">${{$dailyLogSheet['total_total']}}</td>
            <td colspan="3" class="table-success" style="font-size: 1.2em;">Trips</td>
            <td class="table-primary font-weight-bold">{{$dailyLogSheet['nbr_trips']}}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- end table -->





        <footer>

        </footer>

    </body>
</html>