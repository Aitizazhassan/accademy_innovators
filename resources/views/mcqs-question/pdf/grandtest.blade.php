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
        .solution-label {
            margin-bottom: 0px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px !important;
        }
        .qr-codes {
            text-align: end;
        }
        .qr-codes p {
            margin-bottom: 0px;
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
                @if($questions->count() > 0)
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
                            <td>
                                <div class="qr-codes">
                                    @if ($mcq->qr_code_english)
                                        <div class="english-solution">
                                            <p>English</p>{!! $mcq->qr_code_english !!}
                                        </div>
                                    @endif
                                    @if ($mcq->qr_code_urdu)
                                    <div class="urdu-solution">
                                        <p>Urdu</p>{!! $mcq->qr_code_urdu !!}
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </table>
                    @endforeach
                    @else
                    <p style="">No Mcqs Found</p>
                @endif
        @endforeach
        <hr>
        @else
            <p style="text-align: center">No Mcqs Found</p>
        @endif
    @endfor
</body>
</html>
