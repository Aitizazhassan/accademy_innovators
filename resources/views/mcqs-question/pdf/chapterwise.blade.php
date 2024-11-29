<!DOCTYPE html>
<html>
<head>
    <title>Chapter-wise MCQs</title>
    <style>

        /* body {
            font-family: 'Arial', sans-serif; 
            font-size: 12pt;
            line-height: 1.5;
            border: 1px dashed #000;
            color: #333;
            margin: 0px;
            background-repeat: no-repeat;
            background-position: center;
            padding: 0px 20px;
            background-image: url("data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/watermark.png'))) }}");
            background-position: center;
            background-repeat: no-repeat;
            background-size: 90% 80%;
        } */

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
            /* border-bottom: 1px solid #ddd; */
            padding-bottom: 5px;
        }

        h3 {
            font-size: 14pt;
            font-weight: bold;
        }

        /* .question {
            margin: 20px 0;
        }

        .answers {
            margin-left: 20px;
            list-style-type: none; 
        }

        .answers li {
            margin: 5px 0;
            font-size: 12pt;
        } */

        .footer {
            position: fixed;
            bottom: 30px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10pt;
            color: #888;
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
    <script src="http://latex.codecogs.com/latexit.js"></script>
</head>
<body>
    <div class="header">
        <h2>Chapter-wise MCQs</h2>
    </div>
        @foreach($mcqs as $chapter => $questions)
            @if(count($questions[$subject->name]) > 0)
                <h4 style="text-transform: capitalize">Subject: {{ $subject->name }}</h4>
                <h5 style="text-transform: capitalize">Chapter: {{ $chapter }}</h5>
            @endif
                @foreach($questions[$subject->name] as $mcq)
                    <table>
                        <tr>
                            <td>
                                {{-- <table style="margin: 0%;padding:0%;">
                                    <tr>
                                        <td style=""><strong>{{ $loop->iteration }})</strong></td>
                                        <td style="">
                                            {!! $mcq->statement !!}
                                        </td>
                                    </tr>    
                                </table> --}}
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
        @endforeach
</body>
</html>
