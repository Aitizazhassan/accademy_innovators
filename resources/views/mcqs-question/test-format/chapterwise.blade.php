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
    </style>
</head>
<body>
    <div class="header">
        <h1>Chapter-wise MCQs</h1>
    </div>

    <!-- Display MCQs -->
    @foreach($mcqs as $chapter => $questions)
        @if(count($questions[$subject->name]) > 0)
            <h3>Chapter: {{ $chapter }}</h3>
            <h4>Subject: {{ $subject->name }}</h4>
        @endif
        @foreach($questions[$subject->name] as $mcq)
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
                </tr>
            </table>
        @endforeach
    @endforeach

    <!-- Solution Links at the end of the page -->
    <div class="solution-section">
        <h3>Solution Links:</h3>
        @foreach($mcqs as $chapter => $questions)
            @if(count($questions[$subject->name]) > 0)
                <h3>Chapter: {{ $chapter }}</h3>
                <h4>Subject: {{ $subject->name }}</h4>
            @endif
            @foreach($questions[$subject->name] as $mcq)
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
        @endforeach
    </div>
</body>
</html>
