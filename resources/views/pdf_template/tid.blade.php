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
        <h2 style="text-align: center;">Terminal Interchange Document (T.I.D)</h2>

        <table>
            <tr>
                <td>Registado Por/Gate Clerk: <span class="highlight">{{$tid['user']}}</span></td>
                <td>Data de Registo/Date: <span class="highlight">{{$tid['operation_date']}}</span></td>
            </tr>
            <tr>
                <td>Nº de Transação/Transaction Nº: <span class="highlight">{{$tid['transaction_number']}}</span></td>
                <td>Transação/Transaction: <span class="highlight">{{$tid['transaction_type']}}</span></td>
            </tr>
            <tr>
                <td>Localização/Location: <span class="highlight">{{$tid['location']}}</span></td>
                <td>ISO: <span class="highlight">{{$tid['iso_code']}}</span></td>
            </tr>
            <tr>
                <td>Transportadora/Truck Company: <span class="highlight">{{$tid['trucking_company']}}</span></td>
                <td>Matrícula/Truck License: <span class="highlight">{{$tid['license_plate']}}</span></td>
            </tr>
            <tr>
                <td>Nome do Motorista/ Truck Driver Name: <span class="highlight">{{$tid['driver_name']}}</span></td>
                <td>Driver License: <span class="highlight">{{$tid['driver_license']}}</span></td>
            </tr>
        </table>
    
    
        <section style="text-align: center; margin-top: 450px;">
            <p>
                Todos serviços já estão pagos. Em caso de cobranças ilícitas ou mau atendimento, ligue <span class="highlight">8000 000 888</span>
            </p>
            <p>
                All services have already been paid for. In case of unauthorized charges or bad service, call <span class="highlight">8000 000 888</span>
            </p>
        </section>
    </div>
</body>
</html>