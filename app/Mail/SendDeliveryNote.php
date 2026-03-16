<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class SendDeliveryNote extends Mailable
{
    use Queueable, SerializesModels;
    private $transaction;
    private $deliveryNote;
    private $createdby;

    /**
     * Create a new message instance.
     */
    public function __construct($transaction, $deliveryNote, $createdby)
    {
        //
        $this->transaction = $transaction;
        $this->deliveryNote = $deliveryNote;
        $this->createdby = $createdby;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'C-GATE 2.0 Delivery Note',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
       return new Content(
            markdown: 'mails.delivery_note',
            with:[
                'transaction' => $this->transaction,
                'createdby' => $this->createdby
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {

        $attachments = [];
        $imagePath = public_path('cdm-logo.jpg');
        $imageBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($imagePath));
        
        
        try {
            foreach ($this->deliveryNote as $index => $document) {
                        // Gerar PDF para cada documento
                        $pdf = Pdf::loadView('pdf.delivery_note', compact('document','imageBase64'))
                            ->setOptions([
                                'isRemoteEnabled' => true,
                                'isHtml5ParserEnabled' => true,
                                'isPhpEnabled' => true,
                                'dpi' => 96,
                                'defaultFont' => 'Arial'
                            ])
                            ->setPaper([0, 0, 375, 841.89], 'portrait'); // 80mm width
                        
                        // Nome do arquivo baseado no tipo de documento e número da transação
                        $filename = "DeliveryNote_{$document['document_type']}_{$document['transaction_number']}.pdf";
                        
                        $attachments[] = Attachment::fromData(
                            fn () => $pdf->output(), 
                            $filename
                        )->withMime('application/pdf');
        }
            
            
        } catch (\Exception $e) {
            // Em caso de erro, gerar PDF padrão
            Log::error('Erro ao buscar documentos do endpoint: ' . $e->getMessage());
        }
        
        return $attachments;
    }
}
