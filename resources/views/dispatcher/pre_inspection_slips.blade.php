<html>
<head>
    <style>
        @include('dispatcher.inc.pdf_css_table')
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
            left: 2cm;
            right: 0cm;
            height: 0cm;
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

            bottom: 0.5cm;
            left: 2cm;
            right: 0cm;
            height: 1cm;
        }

    </style>
</head>
<body>
<header>

    <h1 class=" font-weight-bold ">
        Chilliwack Taxi
    </h1>
    <h4 class="font-weight-bold text-muted">Pre-Inspection Slip</h4>
    <hr>
</header>

<div class="footer">
    <span class="pagenum font-weight-bold" style="font-size: large"></span>
</div>


<!-- table -->
<div class="table-responsive w-100 mt-5">
    <table class="table table-striped" style="table-layout:fixed">
        <thead class="thead-dark">
        <tr>
            <th scope="col" class="w-50">Title</th>
            <th scope="col">Value</th>
        </tr>
        </thead>

        <tbody>
        <tr class="table-primary">
            <td colspan="2" class="text-center text-uppercase font-weight-bold">
                Driver
            </td>
        </tr>
        <tr>
            <td class="text-uppercase font-weight-bold">Driver Name</td>
            <td class="text-uppercase font-weight-bold">{{$slip['driverName']}}</td>
        </tr>
        <tr>
            <td class="text-uppercase font-weight-bold">Badge Id</td>
            <td class="text-uppercase font-weight-bold">{{$slip['ownerBadgeId']}}</td>
        </tr>

        <tr>
            <td class="text-uppercase font-weight-bold">driver Remarks</td>
            <td class="text-uppercase font-italic text-primary">{{$slip['driverRemarks']}}</td>
        </tr>

        <tr>
            <td class="text-uppercase font-weight-bold">this slip was created at</td>
            <td class="text-uppercase font-weight-bold">{{$slip['created_at']}}</td>
        </tr>
        <tr>
            <td class="text-uppercase font-weight-bold">this slip's last update was at</td>
            <td class="text-uppercase font-weight-bold">{{$slip['updated_at']}}</td>
        </tr>


        <tr>
            <td class="text-uppercase font-weight-bold">Affected Corrected</td>
            @if($slip['aboveAffectedCorrected']=='yes')
                <td
                    class="text-uppercase font-weight-bold table-success">{{$slip['aboveAffectedCorrected']}}
                </td>
            @else
                <td class="text-uppercase font-weight-bold table-warning">
                    {{$slip['aboveAffectedCorrected']}}
                </td>
            @endif
        </tr>


        <tr class="table-primary">
            <td colspan="2" class="text-center text-uppercase font-weight-bold">
                Shift
            </td>
        </tr>
        <tr>
            <td class="text-uppercase font-weight-bold">date</td>
            <td class="text-uppercase font-weight-bold">{{$slip['date']}}</td>
        </tr>

        <tr>
            <td class="text-uppercase font-weight-bold">shift Type</td>
            <td class="text-uppercase font-weight-bold">{{$slip['shiftType']}}</td>
        </tr>
        <tr>
            <td class="text-uppercase font-weight-bold">vehicle Name</td>
            <td class="text-uppercase font-weight-bold">{{$slip['vehicle']['vehicleName']}}</td>
        </tr>

        <tr>
            <td class="text-uppercase font-weight-bold">licensed Plate</td>
            <td class="text-uppercase font-weight-bold">{{$slip['licensedPlate']}}</td>
        </tr>
        <tr>
            <td class="text-uppercase font-weight-bold">odometer</td>
            <td class="text-uppercase font-weight-bold">{{$slip['odometer']}}</td>
        </tr>


        <tr class="table-primary">
            <td colspan="2" class="text-center text-uppercase font-weight-bold">
                Check List
            </td>
        </tr>
        <tr>
            <td class="text-uppercase font-weight-bold">glass And Mirrors</td>
            @if($slip['glassAndMirrors']=='yes')
                <td
                    class="text-uppercase font-weight-bold table-success">{{$slip['glassAndMirrors']}}
                </td>
            @else
                <td class="text-uppercase font-weight-bold table-warning">{{$slip['glassAndMirrors']}}
                </td>
            @endif
        </tr>

        <tr>
            <td class="text-uppercase font-weight-bold">heater Defroster</td>
            @if($slip['heaterDefroster']=='yes')
                <td
                    class="text-uppercase font-weight-bold table-success">{{$slip['heaterDefroster']}}
                </td>
            @else
                <td class="text-uppercase font-weight-bold table-warning">{{$slip['heaterDefroster']}}
                </td>
            @endif

        </tr>

        <tr>
            <td class="text-uppercase font-weight-bold">horn</td>
            @if($slip['horn']=='yes')
                <td class="text-uppercase font-weight-bold table-success">
                    {{$slip['horn']}}
                </td>
            @else
                <td class="text-uppercase font-weight-bold table-warning">
                    {{$slip['horn']}}
                </td>
            @endif
        </tr>

        <tr>
            <td class="text-uppercase font-weight-bold">hydraulic Brake System</td>
            @if($slip['hydraulicBrakeSystem']=='yes')
                <td
                    class="text-uppercase font-weight-bold table-success">{{$slip['hydraulicBrakeSystem']}}
                </td>
            @else
                <td class="text-uppercase font-weight-bold table-warning">
                    {{$slip['hydraulicBrakeSystem']}}
                </td>
            @endif
        </tr>

        <tr>
            <td class="text-uppercase font-weight-bold">interior Exterior</td>
            @if($slip['interiorExterior']=='yes')
                <td
                    class="text-uppercase font-weight-bold table-success">{{$slip['interiorExterior']}}
                </td>
            @else
                <td class="text-uppercase font-weight-bold table-warning">{{$slip['interiorExterior']}}
                </td>
            @endif
        </tr>

        <tr>
            <td class="text-uppercase font-weight-bold">lampsAndIndicators</td>
            @if($slip['lampsAndIndicators']=='yes')
                <td
                    class="text-uppercase font-weight-bold table-success">{{$slip['lampsAndIndicators']}}
                </td>
            @else
                <td class="text-uppercase font-weight-bold table-warning">
                    {{$slip['lampsAndIndicators']}}
                </td>
            @endif
        </tr>


        <tr>
            <td class="text-uppercase font-weight-bold">steering Tires</td>
            @if($slip['steeringTires']=='yes')
                <td class="text-uppercase font-weight-bold table-success">
                    {{$slip['steeringTires']}}
                </td>
            @else
                <td class="text-uppercase font-weight-bold table-warning">{{$slip['steeringTires']}}</td>
            @endif
        </tr>
        <tr>
            <td class="text-uppercase font-weight-bold">turn Jake</td>
            @if($slip['turnJake']=='yes')
                <td class="text-uppercase font-weight-bold table-success">
                    {{$slip['turnJake']}}
                </td>
            @else
                <td class="text-uppercase font-weight-bold table-warning">{{$slip['turnJake']}}</td>
            @endif
        </tr>

        <tr>
            <td class="text-uppercase font-weight-bold">wheel Chair Lift And Emergency Equipment</td>
            @if($slip['wheelChairLiftAndEmergencyEquipment']=='yes')
                <td
                    class="text-uppercase font-weight-bold table-success">
                    {{$slip['wheelChairLiftAndEmergencyEquipment']}}
                </td>
            @else
                <td class="text-uppercase font-weight-bold table-warning">
                    {{$slip['wheelChairLiftAndEmergencyEquipment']}}
                </td>
            @endif
        </tr>
        <tr>
            <td class="text-uppercase font-weight-bold">wheels And Hubs And Fastners</td>
            @if($slip['wheelsAndHubsAndFastners']=='yes')
                <td
                    class="text-uppercase font-weight-bold table-success">{{$slip['wheelsAndHubsAndFastners']}}
                </td>
            @else
                <td class="text-uppercase font-weight-bold table-warning">
                    {{$slip['wheelsAndHubsAndFastners']}}
                </td>
            @endif
        </tr>
        <tr>
            <td class="text-uppercase font-weight-bold">wind Shield Wipers And Washers</td>
            @if($slip['windShieldWipersAndWashers']=='yes')
                <td
                    class="text-uppercase font-weight-bold table-success">
                    {{$slip['windShieldWipersAndWashers']}}
                </td>
            @else
                <td class="text-uppercase font-weight-bold table-warning ">
                    {{$slip['windShieldWipersAndWashers']}}
                </td>
            @endif
        </tr>

        <tr class=" mt-4">
            <td colspan="2" class=" text-center text-uppercase font-weight-bold">
                Safe to drive ?
            </td>
        </tr>
        <tr style="font-size: 1.4em">
            @if($slip['safeToDrive']=='yes')
                <td colspan="2" class="text-uppercase font-weight-bold table-success text-center">
                    {{$slip['safeToDrive']}}
                </td>
            @else
                <td colspan="2" class="text-uppercase font-weight-bold  table-danger text-center">
                    Not Safe To Drive
                </td>
            @endif
        </tr>
        </tbody>
    </table>
    <footer>

    </footer>
</div>


</body>
</html>
