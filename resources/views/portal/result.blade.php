@extends('layouts.portal')

@section('content')
<div style="margin-bottom: 15px;"><a href="{{ route('portal.dashboard') }}" style="color:#00a896; font-weight:bold; text-decoration:none;">← Return to Main Dashboard</a></div>

<div id="printable-area" class="panel-card" style="border: 2px solid #111; padding:0; background:white;">
    <div style="background: #002f34; color: white; padding: 20px; display:flex; justify-content:space-between; align-items:center;">
        <div style="display:flex; align-items:center;">
            <img src="https://www.comsats.edu.pk/images/logo.png" style="width:45px; margin-right:15px; filter:brightness(0) invert(1);">
            <h3 style="margin:0; text-transform:uppercase; letter-spacing:0.5px;">COMSATS University Islamabad</h3>
        </div>
        <span style="font-size:12px; opacity:0.8;">Official Academic Status Card</span>
    </div>

    <div style="padding: 20px; display:grid; grid-template-columns: 1fr 1fr; gap:10px; background:#fafafa; border-bottom:1px solid #111; font-size:14px;">
        <div><b>Student Identity:</b> {{ $student->name }}</div>
        <div><b>Reg Code:</b> {{ $student->reg_no }}</div>
        <div><b>Mapped Curriculum:</b> BS {{ $student->department }}</div>
        <div><b>Active Node:</b> Semester {{ $student->semester }}</div>
    </div>

    <table class="result-table" style="width:100%; border:none;">
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course Description Title</th>
                <th>Units (Cr.)</th>
                <th>Marks</th>
                <th>Grade</th>
                <th>Value (G.P.)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($processedMatrix as $c)
            <tr>
                <td>{{ $c['no'] }}</td>
                <td style="text-align:left; padding-left:12px;">{{ $c['title'] }}</td>
                <td><b>{{ $c['cr'] }}</b></td>
                <td>{{ $c['marks'] }}</td>
                <td><b>{{ $c['lg'] }}</b></td>
                <td><b>{{ number_format($c['gp'], 2) }}</b></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="ai-insight-box" style="padding:15px; background:#f0fdfa; border-left:4px solid #00a896; margin:20px; border-radius:0 6px 6px 0;">
        <div style="font-weight:bold; color:#008f7f; margin-bottom:5px;">🤖 Predictive AI Performance Advisor Insight</div>
        <p style="margin:0; font-size:13px; line-height:1.4; color:#0f766e;">{{ $aiAdvice }}</p>
    </div>

    <div style="padding: 20px; background: #fafafa; border-top:1px solid #111; font-size:16px; font-weight:bold;">
        Calculated Session CGPA Score: <span style="color:#00a896;">{{ $cgpa }}</span>
        <span style="float:right;">Scholastic Status: {{ $cgpa >= 2.00 ? 'GAS' : 'PROBATION' }}</span>
        <div style="clear:both;"></div>
    </div>
</div>

<div style="text-align:right; max-width:800px; margin:0 auto;">
    <button onclick="downloadPDF()" style="background:#0284c7; color:white; border:none; padding:14px 28px; border-radius:6px; font-weight:bold; cursor:pointer; font-size:14px;">Download Official Document View</button>
</div>

<script>
function downloadPDF() {
    const element = document.getElementById('printable-area');
    html2pdf().set({
        margin: 0.2,
        filename: 'CUI_Academic_Transcript_{{ $student->reg_no }}.pdf',
        image: { type: 'jpeg', quality: 1.0 },
        html2canvas: { scale: 2, scrollY: 0, backgroundColor: "#ffffff" },
        jsPDF: { format: 'a4', orientation: 'portrait' }
    }).from(element).save();
}
</script>
@endsection
