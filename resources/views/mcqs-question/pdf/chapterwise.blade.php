<!DOCTYPE html>
<html>
<head>
    <title>Chapter-wise MCQs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
            border: 1px solid #000;
            padding: 20px;
            background-repeat: no-repeat;
            background-position: center;
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
            margin-bottom: 20px;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Chapter-wise MCQs</h1>
    </div>
        <h2>Subject: {{ $subject->name }}</h2>
        @foreach($chapters as $chapter)
            @php
             $chapter = \App\Models\Chapter::find($chapter);
             if(count($mcqs[$subject->name][$chapter->name]) == 0)
             {
                continue;
             }
            @endphp
            <h3>Chapter: {{ $chapter->name }}</h3>
            @foreach($mcqs[$subject->name][$chapter->name] as $mcq)
                <table>
                    <tr>
                        <td>
                            <div class="content">
                                <p><strong>{{ $loop->iteration }})</strong> {!! strip_tags($mcq->statement) !!}</p>
                                <p><strong>A)</strong> {!! strip_tags($mcq->optionA) !!}</p>
                                <p><strong>B)</strong> {!! strip_tags($mcq->optionB) !!}</p>
                                <p><strong>C)</strong> {!! strip_tags($mcq->optionC) !!}</p>
                                <p><strong>D)</strong> {!! strip_tags($mcq->optionD) !!}</p>
                            </div>
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
