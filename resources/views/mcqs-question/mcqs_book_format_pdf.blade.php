<!DOCTYPE html>
<html>
<head>
    <title>MCQs PDF</title>
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
            background-size: 90% 80%;
            opacity: 0.5; /* Adjust this value to lower the opacity */
            z-index: -1;
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


        /* Watermark for all pages */
        @page {
            size: A4;
            margin: 50px;
            background-image: url("data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/background.png'))) }}");
            background-position: center;
            background-repeat: no-repeat;
            background-size: 50%;
        }
        .solution-label {
            margin-bottom: 0px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px;
        }
        table, th, td {
            /* border: 1px solid black; */
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
    <script type="text/javascript" id="MathJax-script" async
        src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js">
    </script>
</head>
<body>
    <div class="header">
        <h1>MCQs Book Format</h1>
    </div>
    @foreach($mcqs as $mcq)
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
                    <div class="mt-0" style="display: inline-block;margin-top:5px;">
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
</body>
</html>
