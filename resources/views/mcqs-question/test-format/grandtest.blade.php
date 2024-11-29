<!DOCTYPE html>
<html>
<head>
    <title>Grand Test MCQs</title>
    <style>
        body {
            font-family: 'Arial', sans-serif; 
            font-size: 12pt;
            line-height: 1.5;
            border: 1px dashed #000;
            color: #333;
            margin: 0px;
            padding: 0px 20px;
            position: relative; /* Added to allow ::before positioning */
        }

        /* Pseudo-element for watermark */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/watermark.png'))) }}");
            background-position: center;
            background-repeat: no-repeat;
            background-size: 90% 70%;
            opacity: 0.5; /* Adjust this value to lower the opacity */
            z-index: -1;
        }
        
        h1, h2, h3 {
            margin: 10px 0;
        }

        h1 {
            font-size: 24pt;
            text-align: center;
            font-weight: bold;
        }

        h2 {
            font-size: 18pt;
            font-weight: bold;
            padding-bottom: 5px;
        }

        h3 {
            font-size: 14pt;
            font-weight: bold;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .solution-section {
            page-break-before: always;
        }
        .qr-codes {
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }
        .qr-codes p {
            margin: 0 10px 0 0; /* Add some space between the number and the QR code */
        }
        .qr-code img {
            width: 80px; /* Set a fixed width for QR codes to align them properly */
            height: 80px;
            margin-right: 10px;
            margin-top: 10px;
        }
        .qr-code span {
            display: inline-block;
            margin-left: 10px;
        }
        .solution-label {
            font-weight: bold;
            margin-right: 10px;
        }
        p {
            margin: 0px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Grand Test MCQs</h2>
    </div>
    @for($i = 0; $i < $numGrandTests; $i++)
        <h4 style="text-align:center;">Grand Test {{ $i + 1 }}</h4>
        @isset($mcqs[$i])
        @foreach($mcqs[$i] as $subject => $questions)
                <h5 style="text-transform: capitalize">Subject: {{ $subject }}</h5>
                @foreach($questions as $mcq)
                    <table>
                        <tr>
                            <td>
                                <div style="display: inline-block;padding-right:10px;">
                                    <strong>{{ $loop->iteration }})</strong>
                                </div>
                                <div class="mt-0" style="display: inline-block;">
                                    {!! $mcq->statement !!}
                                </div>
                                <ol style="padding-top:0px;margin-top:0px;">
                                    <li> {!! $mcq->optionA !!}</li>
                                    <li> {!! $mcq->optionB !!}</li>
                                    <li> {!! $mcq->optionC !!}</li>
                                    <li> {!! $mcq->optionD !!}</li>
                                </ol>
                            </td>
                        </tr>
                    </table>
                @endforeach
        @endforeach
        <hr>
        @else
            <p style="text-align:center">No Mcqs Found</p>
        @endif
    @endfor

    <!-- Solution Links at the end of the page -->
    <div class="solution-section">
        <div class="header">
            <h2>Solution Links:</h2>
        </div>
        
        @for($i = 0; $i < $numGrandTests; $i++)
                @isset($mcqs[$i])
                <h4 style="text-align:center;">Grand Test {{ $i + 1 }}</h4>
                @foreach($mcqs[$i] as $subject => $questions)
                        @if($questions->count() > 0)
                        <h5 style="text-transform: capitalize">Subject: {{ $subject }}</h5>
                            @foreach($questions as $mcq)
                            <div class="qr-codes">
                                <span<strong>{{ $loop->iteration }})</strong></span>
                                @if ($mcq->qr_code_english)
                                    <span class="qr-code">
                                        <span class="solution-label">English:</span> 
                                        {!! $mcq->qr_code_english !!}
                                    </span>
                                @else
                                    <span class="solution-label">English: No Solution</span>
                                @endif
                                @if ($mcq->qr_code_urdu)
                                    <span class="qr-code">
                                        <span class="solution-label">Urdu:</span>
                                        {!! $mcq->qr_code_urdu !!}
                                    </span>
                                @else
                                    <span class="solution-label">Urdu: No Solution</span>
                                @endif
                            </div><br><br>
                            @endforeach
                        @endif  
                @endforeach
                @endif
        @endfor
    </div>
</body>
</html>
