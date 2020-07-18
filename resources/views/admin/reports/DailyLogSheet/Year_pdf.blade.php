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
                            Driver: {{$dailyLogSheet['name']}} - Year: {{$dailyLogSheet['date']}}
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


            <div class="table-responsive">

            <br>
            <table class="table table-bordered table-hover">
            <tbody>
              <tr class="table-primary text-uppercase text-center font-weight-bold" >
                      <td >Months</td>
                      <td >Fare</td>
                      <td >Tip</td>
                      <td >Total</td>
              </tr>

              @foreach ($dailyLogSheet['months'] as $month)
              <tr>
                        <td class=" text-uppercase font-weight-bold" >
                          {{$month->name}}
                        </td>
                        <td>
                          ${{$month->fare}}
                        </td>
                        <td>
                          ${{$month->tip}}
                        </td>
                        <td>
                          ${{$month->total}}
                        </td>
                        
              </tr>
              @endforeach

              <tr>
                  <td class="table-success text-uppercase text-center font-weight-bold">Totals</td>
                  <td >${{$dailyLogSheet['total_fare']}}</td>
                  <td >${{$dailyLogSheet['total_tip']}}</td>
                  <td >${{$dailyLogSheet['total_total']}}</td>
              </tr>

            </tbody>
            </table>
            </div>



    <!-- end table -->





        <footer>

        </footer>

    </body>
</html>