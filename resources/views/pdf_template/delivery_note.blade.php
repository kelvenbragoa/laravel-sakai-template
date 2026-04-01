<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My HTML Document</title>
    <style>
        body {
            font-size: 16px;
        }
        table {
          border-collapse: collapse;
          width: 100%; /* Set full width */
          margin-bottom: 20px;
          line-height: 14px;
        }
        th, td {
          border: 2px solid black; /* Add borders to cells */
          padding:5px; /* Add padding for spacing */
        }
        td {
          text-align: left; /* Optional: Align content to the left */
        }
        /* Dynamic column width calculation */
        td {
          width: calc(100% / (3));  /* Replace with actual number */
        }
        .highlight{
            font-weight: bold;
        }
        .delivery-note{
            margin-left: 30px;
            margin-right: 30px;
        }
        li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="delivery-note">
        <h1 style="text-align: center;">Delivery Note/Termo de Entrega</h1>
        <table>
            <tr>
                <td style="width: 50%;">Registado Por/Gate Clerk: <span class="highlight">{{$dn['user']}}</span></td>
                <td style="width: 50%;">Data de Registo/Date: <span class="highlight">{{$dn['operation_date']}}</span></td>
            </tr>
        </table>
        <table>
            <tr>
                <td>Transação/Transaction: <span class="highlight">{{$dn['transaction_type']}}</span></td>
                <td>Nº de Transação/Transaction Nº: <span class="highlight">{{$dn['transaction_number']}}</span></td>
            </tr>
            <tr>
                <td>Booking/Order: <span class="highlight">{{$dn['bookink_number']}}</span></td>
                <td>Contentor/Container: <span class="highlight">{{$dn['container_number']}}</span></td>
            </tr>
            <tr>
                <td>Armador/Line: <span class="highlight">{{$dn['line']}}</span></td>
                <td>Selo/Seal Number: <span class="highlight">{{$dn['seal_number_1']}}</span></td>
            </tr>
            <tr>
                <td>Navio/Vessel: <span class="highlight">{{$dn['vessel']}}</span></td>
                <td>Viagem/Voyage Nº: <span class="highlight">{{$dn['voyage']}}</span></td>
            </tr>
            <tr>
                <td>IMDG: <span class="highlight">{{$dn['imdg']}}</span></td>
                <td>ISO: <span class="highlight">{{$dn['iso_code']}}</span></td>
            </tr>
            <tr>
                <td>Destino/Destination: <span class="highlight">{{$dn['destination']}}</span></td>
                <td>Origem/Origin: <span class="highlight">{{$dn['origin']}}</span></td>
            </tr>
            <tr>
                <td>Peso/Weight: <span class="highlight">{{$dn['weighth']}}</span></td>
                <td>Position: <span class="highlight">{{$dn['position']}}</span></td>
            </tr>
            <tr>
                <td>Matrícula/License Plate: <span class="highlight">{{$dn['license_plate']}}</span></td>
                <td>Transportadora/Truck Company: <span class="highlight">{{$dn['trucking_company']}}</span></td>
            </tr>
            <tr>
                <td>Trailer Nbr/Trailer Número: <span class="highlight"></span></td>
                <td><span class="highlight"></span></td>
            </tr>
            <tr>
                <td>Driver Name: <span class="highlight">{{$dn['driver_name']}}</span></td>
                <td>Driver License: <span class="highlight">{{$dn['driver_license']}}</span></td>
            </tr>
        </table>
    
        <table>
            <span>Código de avaria/Damage Codes</span>
            
            <tr>
                <td>Component: <span class="highlight"></span></td>
                <td>Damage Type: <span class="highlight"></span></td>
                <td>Severity: <span class="highlight"></span></td>
            </tr>
        </table>
    
        <section style="text-align: justify;">
            <ol>
                <li>
                    Os abaixo assinados declaram ter inspecionado o contentor e recebido/entregue em boas condições excepto pelas avarias acima mencionadas.
                    <br>
                    The undersigned declared to have inspected the container and receive/delivered in good conditions except the damages above mentioned.
                </li>
                <li>
                    Os abaixo assinados confirmam que caso o transporte envolva carga perigosa, este é realizado obedecendo a legislação/regulamentação em vigor.
                    <br>
                    The undersigned confirm that if the transport involves dangerous cargo, it is carried out in compliance with current legislation/regulations.
                </li>
            </ol>
        </section>

        <section>
            <table style="line-height: 20px;">
                <tr>
                    <td style="border:none">
                        _________
                        <br>
                        Data/Date
                    </td>
                    <td style="border:none">
                        ______________________
                        <br>
                        1. Assinatura Cornelder
                    </td>
                    <td style="border:none">
                        ______________________
                        <br>
                        2. Assinatura Transportador
                    </td>
                </tr>
                <tr>
                    <td style="border:none"></td>
                    <td style="border:none"></td>
                    <td style="border:none">
                        ______________________
                        <br>
                        BI/Passport
                    </td>
                </tr>
            </table>
        </section>
    </div>
</body>
</html>