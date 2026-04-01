<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thermal Print - Delivery Note</title>
    <style>
      @page {
        margin: 5mm;
        size: 88mm auto;
      }
      
      body {
        width: 120mm;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        font-size: 11px;
        line-height: 1.2;
      }
      
      .header {
        text-align: center;
        font-weight: bold;
        margin-bottom: 20px;
        margin-top: 10px;
      }
      
      .header table {
        width: 100%;
        border-collapse: collapse;
      }
      
      .header td {
        text-align: center;
        vertical-align: middle;
      }
      
      .content {
        margin: 0 3mm;
      }
      
      .footer {
        text-align: center;
        margin-top: 20px;
      }
      
      .bold {
        font-weight: bold;
      }
      
      p {
        font-size: 12px;
        margin: 3px 0;
      }
      
      span {
        font-size: 14px;
      }
      
      .info-row {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 5px;
        table-layout: fixed;
      }
      
      .info-row td {
        vertical-align: top;
        padding: 2px 1px;
        word-wrap: break-word;
      }
      
      .left-col {
        text-align: left;
        width: 33%;
      }
      
      .center-col {
        text-align: center;
        width: 34%;
      }
      
      .right-col {
        text-align: right;
        width: 33%;
      }
      
      .two-col-left {
        text-align: left;
        width: 50%;
      }
      
      .two-col-right {
        text-align: right;
        width: 50%;
      }
      
      hr {
        border: none;
        border-top: 1px solid #000;
        margin: 8px 0;
      }
      
      .signature-cell {
        text-align: left;
        vertical-align: top;
      }
      
      .signature-cell img {
        max-width: 80px;
        height: auto;
      }
    </style>
  </head>
  <body>
    <div class="header">
      <table class="info-row">
        <tr>
          <td style="width: 30%; text-align: center;">
            <img src="{{ $imageBase64 }}" width="80" height="80" />
          </td>
          <td style="width: 70%; text-align: center;">
            <h2 style="margin: 5px 0;">Delivery Note</h2>
            <h2 style="margin: 5px 0;">Termo de Entrega</h2>
          </td>
        </tr>
      </table>
    </div>
    
    <div class="content">
      <table class="info-row">
        <tr>
          <td class="left-col">
            <p>Registado por/Gate clerk:</p>
            <span class="bold">{{ $document['user'] ?? 'N/A' }}</span>
          </td>
          <td class="center-col">
            <p>Date:</p>
            <span class="bold">{{ isset($document['operation_date']) ? \Carbon\Carbon::parse($document['operation_date'])->format('Y-m-d') : '2024-10-02' }}</span>
          </td>
          <td class="right-col">
            <p>Time:</p>
            <span class="bold">{{ isset($document['operation_date']) ? \Carbon\Carbon::parse($document['operation_date'])->format('H:i:s') : 'N/A' }}</span>
          </td>
        </tr>
      </table>
      <hr />

      <table class="info-row">
        <tr>
          <td class="two-col-left">
            <p>Transação/Transaction:</p>
            <span class="bold">{{ $document['transaction_type'] ?? 'N/A' }}</span>
          </td>
          <td class="two-col-right">
            <p>Transaction No:</p>
            <span class="bold">{{ $document['transaction_number'] ?? 'N/A' }}</span>
          </td>
        </tr>
      </table>
      <hr />

      <table class="info-row">
        <tr>
          <td class="left-col">
            <p>Booking/Order:</p>
            <span class="bold">{{ $document['bookink_number'] ?? 'N/A' }}</span>
          </td>
          <td class="center-col">
            <p>Contentor/Container:</p>
            <span class="bold">{{ $document['container_number'] ?? 'N/A' }}</span>
          </td>
          <td class="right-col">
            <p>ISO:</p>
            <span class="bold">{{ $document['iso_code'] ?? 'N/A' }}</span>
          </td>
        </tr>
      </table>
      <hr />

      <table class="info-row">
        <tr>
          <td class="left-col">
            <p>Armador/Line:</p>
            <span class="bold">{{ $document['line'] ?? 'N/A' }}</span>
          </td>
          <td class="center-col">
            <p>Origem/Origin:</p>
            <span class="bold">{{ $document['origin'] ?? 'N/A' }}</span>
          </td>
          <td class="right-col">
            <p>IMDG:</p>
            <span class="bold">{{ $document['imdg'] ?? 'false' }}</span>
          </td>
        </tr>
      </table>
      <hr />

      <table class="info-row">
        <tr>
          <td class="two-col-left">
            <p>Destino/Destination:</p>
            <span class="bold">{{ $document['destination'] ?? 'N/A' }}</span>
          </td>
          <td class="two-col-right">
            <p>Peso/Weight:</p>
            <span class="bold">{{ $document['weighth'] ?? 'N/A' }}</span>
          </td>
        </tr>
      </table>
      <hr />

      <table class="info-row">
        <tr>
          <td class="two-col-left">
            <p>Navio/Vessel:</p>
            <span class="bold">{{ $document['vessel'] ?? 'N/A' }}</span>
          </td>
          <td class="two-col-right">
            <p>Viagem/Voyage No:</p>
            <span class="bold">{{ $document['voyage'] ?? 'N/A' }}</span>
          </td>
        </tr>
      </table>
      <hr />

      <p>Selo/Seal Number:</p>
      <span class="bold">{{ $document['seal_number_1'] ?? 'N/A' }}</span>
      <hr/>

      @if(isset($document['position']))
      <p>Posição/Position:</p>
      <span class="bold">{{ $document['position'] }}</span>
      <hr/>
      @endif

      <table class="info-row">
        <tr>
          <td class="two-col-left">
            <p>Transportadora/Truck Company:</p>
            <span class="bold">{{ $document['trucking_company'] ?? 'N/A' }}</span>
          </td>
          <td class="two-col-right">
            <p>License Plate:</p>
            <span class="bold">{{ $document['license_plate'] ?? 'N/A' }}</span>
          </td>
        </tr>
      </table>
      <hr />

      <p class="bold">Damage Codes:</p>
      <table class="info-row">
        <tr>
          <td class="left-col">
            <p>Component:</p>
            <span class="bold">-</span>
          </td>
          <td class="center-col">
            <p>Damage type:</p>
            <span class="bold">-</span>
          </td>
          <td class="right-col">
            <p>Severity:</p>
            <span class="bold">-</span>
          </td>
        </tr>
      </table>
      <hr />

      <table class="info-row">
        <tr>
          <td class="two-col-left">
            <p>Driver Name:</p>
            <span class="bold">{{ $document['driver_name'] ?? 'N/A' }}</span>
          </td>
          <td class="two-col-right">
            <p>Driver License:</p>
            <span class="bold">{{ $document['driver_license'] ?? 'N/A' }}</span>
          </td>
        </tr>
      </table>
      <hr />

      <table class="info-row">
        <tr>
          <td class="signature-cell" style="width: 60%;">
            <p>Assinatura Transportador:</p>
            <p></p>
          </td>
          <td class="two-col-right" style="width: 40%; vertical-align: top;">
            <p>BI/Passport:</p>
            <p></p>
          </td>
        </tr>
      </table>
      <hr />

      <!-- <p>Assinatura Cornelder:</p>
      <picture>
        <source srcset="./sign2.png" type="image/png" />
        <img height="100" src="./sign2.jpg" alt="Signature" />
      </picture>
      <hr /> -->

    </div>
    <div class="footer">
      <p>&copy; Cornelder de Moçambique, S.A.</p>
    </div>
  </body>
</html>
