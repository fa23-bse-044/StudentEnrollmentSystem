@extends('layouts.portal')

@section('content')
<style>
    .portal-frame { display: flex; min-height: 85vh; background: white; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); overflow: hidden; }
    .sidebar-panel { width: 260px; background: #002f34; color: white; padding: 30px 20px; display: flex; flex-direction: column; justify-content: space-between; }
    .sidebar-menu { list-style: none; padding: 0; margin: 20px 0 0 0; }
    .sidebar-item a { display: block; padding: 12px 15px; color: #cbd5e1; text-decoration: none; border-radius: 6px; font-weight: 500; margin-bottom: 8px; font-size: 14px; transition: 0.2s; }
    .sidebar-item.active a, .sidebar-item a:hover { background: #00a896; color: white; }
    .content-viewport { flex: 1; padding: 40px; background: #f8fafc; overflow-y: auto; }
    .logout-btn { background: #ef4444; color: white; border: none; padding: 10px; width: 100%; border-radius: 6px; font-weight: bold; cursor: pointer; }
    .content-badge { background: #002f34; color: white; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; }
    .topic-tag { background: #f1f5f9; border-left: 3px solid #00a896; padding: 8px 12px; font-size: 13px; color: #475569; border-radius: 0 4px 4px 0; margin-top: 5px; text-align: left; }
    .subject-pill { padding: 6px 14px; font-size: 12px; font-weight: bold; text-decoration: none; border-radius: 20px; transition: 0.2s; }
</style>

<div class="portal-frame">
    <div class="sidebar-panel">
        <div>
            <div style="text-align:center; margin-bottom:25px;">
                <div style="position: relative; width: 85px; height: 85px; margin: 0 auto; cursor: pointer;" onclick="document.getElementById('profile_file_input').click();">
                    @if($user->profile_picture && file_exists(public_path($user->profile_picture)))
                        <img src="{{ asset($user->profile_picture) }}" style="width: 85px; height: 85px; border-radius: 50%; object-fit: cover; border: 3px solid #00a896; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
                    @else
                        <div style="width: 85px; height: 85px; border-radius: 50%; background: #00a896; color: white; font-size: 32px; font-weight: bold; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.5); border-radius: 50%; display: flex; align-items: center; justify-content: center; opacity: 0; transition: 0.2s; color: white; font-size: 11px; font-weight: bold;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">UPDATE</div>
                </div>
                <form id="profile_picture_form" action="{{ route('profile.update_picture') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                    @csrf
                    <input type="file" id="profile_file_input" name="profile_picture" accept="image/*" onchange="document.getElementById('profile_picture_form').submit();">
                </form>
                <h4 style="margin:12px 0 2px 0; letter-spacing:0.5px; font-size:16px;">{{ $user->name }}</h4>
                <small style="color:#00a896; text-transform:uppercase; font-weight:bold;">{{ $user->role }} Area</small>
            </div>
            <ul class="sidebar-menu">
                <li class="sidebar-item {{ $tab === 'course_content' ? 'active' : '' }}"><a href="{{ route('portal.dashboard', ['tab' => 'course_content']) }}">1- Course Content</a></li>
                <li class="sidebar-item {{ $tab === 'registration_card' ? 'active' : '' }}"><a href="{{ route('portal.dashboard', ['tab' => 'registration_card']) }}">2- Registration Card</a></li>
                <li class="sidebar-item {{ $tab === 'quiz_dashboard' ? 'active' : '' }}"><a href="{{ route('portal.dashboard', ['tab' => 'quiz_dashboard', 'active_course' => $activeCourseNo]) }}">4- Manage Quizzes</a></li>
                <li class="sidebar-item {{ $tab === 'assignment_dashboard' ? 'active' : '' }}"><a href="{{ route('portal.dashboard', ['tab' => 'assignment_dashboard', 'active_course' => $activeCourseNo]) }}">5- Manage Assignments</a></li>
                <li class="sidebar-item {{ $tab === 'result' ? 'active' : '' }}"><a href="{{ route('portal.dashboard', ['tab' => 'result']) }}">3- Result Matrix</a></li>
            </ul>
        </div>
        <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="logout-btn">Sign Out</button></form>
    </div>

    <div class="content-viewport">
        @if(session('success')) <div style="background:#22c55e; color:white; padding:12px; border-radius:6px; margin-bottom:15px; font-weight:bold;">{{ session('success') }}</div> @endif
        @if(session('error')) <div style="background:#ef4444; color:white; padding:12px; border-radius:6px; margin-bottom:15px; font-weight:bold;">{{ session('error') }}</div> @endif

        @if($tab === 'course_content')
            @if($user->role === 'faculty')
                <div class="panel-card" style="border-top: 4px solid #00a896; background:white; padding:25px; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.02); margin-bottom:25px;">
                    <h3>{{ $selectedStudent ? 'Modify Student Profile Node' : 'Register New Student Profile Instance' }}</h3>
                    <form action="{{ route('portal.student.store') }}" method="POST" style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                        @csrf
                        @if($selectedStudent) <input type="hidden" name="id" value="{{ $selectedStudent->id }}"> @endif
                        <div><label style="font-size:12px; font-weight:bold; color:#475569;">Student Full Name</label><input type="text" name="name" value="{{ $selectedStudent ? $selectedStudent->name : old('name') }}" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:4px; margin-top:4px;" required></div>
                        <div><label style="font-size:12px; font-weight:bold; color:#475569;">Portal Login Email</label><input type="email" name="student_email" value="{{ $selectedStudent ? $selectedStudent->student_email : old('student_email') }}" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:4px; margin-top:4px;" required></div>
                        <div><label style="font-size:12px; font-weight:bold; color:#475569;">Registration Code</label><input type="text" name="reg_no" value="{{ $selectedStudent ? $selectedStudent->reg_no : old('reg_no') }}" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:4px; margin-top:4px;" required></div>
                        <div><label style="font-size:12px; font-weight:bold; color:#475569;">Active Academic Term</label><select name="semester" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:4px; margin-top:4px;" required>@for($i=1; $i<=8; $i++) <option value="{{ $i }}" {{ ($selectedStudent && $selectedStudent->semester == $i) ? 'selected' : '' }}>Semester {{ $i }}</option> @endfor</select></div>
                        <div style="grid-column: span 2;"><label style="font-size:12px; font-weight:bold; color:#475569;">Department Major</label><select name="department" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:4px; margin-top:4px;" required><option value="BSE" {{ ($selectedStudent && $selectedStudent->department == 'BSE') ? 'selected' : '' }}>Software Engineering (BSE)</option><option value="BCS" {{ ($selectedStudent && $selectedStudent->department == 'BCS') ? 'selected' : '' }}>Computer Science (BCS)</option></select></div>
                        <button type="submit" style="grid-column:span 2; background:#00a896; color:white; border:none; padding:12px; border-radius:6px; font-weight:bold; cursor:pointer;">Commit Registration</button>
                    </form>
                </div>
                <div class="panel-card" style="background:white; padding:25px; border-radius:8px; margin-bottom:25px;">
                    <h3>Registered Students Table</h3>
                    <table style="width:100%; border-collapse:collapse;">
                        <thead><tr style="background:#f8fafc;"><th style="border:1px solid #e2e8f0; padding:12px;">Reg ID</th><th style="border:1px solid #e2e8f0; padding:12px;">Full Name</th><th style="border:1px solid #e2e8f0; padding:12px;">Email</th><th style="border:1px solid #e2e8f0; padding:12px;">Dept</th><th style="border:1px solid #e2e8f0; padding:12px;">Semester</th><th style="border:1px solid #e2e8f0; padding:12px;">Actions</th></tr></thead>
                        <tbody>
                            @foreach($students as $s)
                            <tr style="text-align:center;"><td style="border:1px solid #e2e8f0; padding:12px;"><b>{{ $s->reg_no }}</b></td><td style="border:1px solid #e2e8f0; padding:12px;">{{ $s->name }}</td><td style="border:1px solid #e2e8f0; padding:12px;">{{ $s->student_email }}</td><td style="border:1px solid #e2e8f0; padding:12px;">{{ $s->department }}</td><td style="border:1px solid #e2e8f0; padding:12px;">Sem {{ $s->semester }}</td><td style="border:1px solid #e2e8f0; padding:12px;"><a href="{{ route('portal.dashboard', ['tab' => 'course_content', 'edit_id' => $s->id]) }}" style="color:#eab308; font-weight:bold; text-decoration:none; margin-right:10px;">Edit</a><a href="{{ route('portal.student.destroy', $s->id) }}" onclick="return confirm('Drop student?')" style="color:#ef4444; font-weight:bold; text-decoration:none;">Delete</a></td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="panel-card" style="background:white; padding:25px; border-radius:8px;">
                <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:2px solid #e2e8f0; padding-bottom:12px; margin-bottom:20px;">
                    <h3 style="margin:0;">1- Course Content Syllabus Matrix</h3>
                    @if($studentForCard) <span class="content-badge">BS-{{ $studentForCard->department }} | Sem {{ $studentForCard->semester }}</span> @endif
                </div>
                <table style="width:100%; border-collapse:collapse;">
                    <thead><tr style="background:#f8fafc;"><th style="border:1px solid #e2e8f0; padding:12px; width:100px;">Code</th><th style="border:1px solid #e2e8f0; padding:12px; text-align:left;">Subject Title</th><th style="border:1px solid #e2e8f0; padding:12px; width:100px;">Credit</th><th style="border:1px solid #e2e8f0; padding:12px;">Curricular Course Syllabus</th></tr></thead>
                    <tbody>
                        @foreach($coursesForCard as $c)
                        <tr style="text-align:center;"><td style="border:1px solid #e2e8f0; padding:12px;"><b>{{ $c['no'] }}</b></td><td style="border:1px solid #e2e8f0; padding:12px; text-align:left;"><b>{{ $c['title'] }}</b></td><td style="border:1px solid #e2e8f0; padding:12px;"><span style="background:{{ $c['cr'] == 4 ? '#fef3c7' : '#e0f2fe' }}; padding:4px 8px; border-radius:4px; font-weight:bold;">{{ $c['cr'] }} Cr</span></td><td style="border:1px solid #e2e8f0; padding:12px;"><div class="topic-tag">{{ $c['topics'] }}</div></td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @elseif($tab === 'registration_card')
            <div class="panel-card" style="background:white; padding:25px; border-radius:8px;">
                <h3>2- Course Registration Matrix Card</h3>
                @if($studentForCard)
                    <div style="background:#fff; border:2px solid #00a896; border-radius:8px; padding:20px; margin-top:15px;">
                        <h4 style="margin-top:0; color:#002f34;">Registered Subjects Sheet for: {{ $studentForCard->name }} (BS-{{ $studentForCard->department }} Sem {{ $studentForCard->semester }})</h4>
                        <table style="width:100%; border-collapse:collapse;">
                            <thead><tr style="background:#f8fafc;"><th style="border:1px solid #e2e8f0; padding:12px;">Subject Code</th><th style="border:1px solid #e2e8f0; padding:12px; text-align:left; padding-left:15px;">Course Name</th><th style="border:1px solid #e2e8f0; padding:12px;">Credit Volume</th><th style="border:1px solid #e2e8f0; padding:12px;">Status</th></tr></thead>
                            <tbody>
                                @foreach($coursesForCard as $c)
                                <tr style="text-align:center;"><td style="border:1px solid #e2e8f0; padding:12px;"><b>{{ $c['no'] }}</b></td><td style="border:1px solid #e2e8f0; padding:12px; text-align:left; padding-left:15px; font-weight:500;">{{ $c['title'] }}</td><td style="border:1px solid #e2e8f0; padding:12px;"><span style="background:#e2e8f0; padding:2px 6px; border-radius:4px;">{{ $c['cr'] }} Credits</span></td><td style="border:1px solid #e2e8f0; padding:12px;"><span style="color:#00a896; font-weight:bold;">✓ Enrolled & Registered</span></td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

       @elseif($tab === 'quiz_dashboard')
            <div class="panel-card" style="background:white; padding:25px; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.02);">
                <h3>📋 Core Subject Quiz Marks Registry</h3>

                <div style="margin-bottom:20px; background:#f8fafc; padding:12px; border-radius:6px; border:1px solid #cbd5e1;">
                    <small style="font-weight:bold; color:#475569; display:block; margin-bottom:8px;">Select Subject Workspace Node:</small>
                    <div style="display:flex; flex-wrap:wrap; gap:8px;">
                        @foreach($coursesForCard as $c)
                            <a href="{{ route('portal.dashboard', ['tab' => 'quiz_dashboard', 'active_course' => $c['no']]) }}" class="subject-pill" style="background:{{ $activeCourseNo === $c['no'] ? '#00a896' : '#e2e8f0' }}; color:{{ $activeCourseNo === $c['no'] ? 'white' : '#334155' }};">{{ $c['no'] }}</a>
                        @endforeach
                    </div>
                </div>

                @if($user->role === 'faculty')
                    <div style="background:#fafafa; border:1px solid #cbd5e1; padding:15px; border-radius:6px; margin-bottom:20px;">
                        <h4 style="margin:0 0 10px 0;">Add/Record Quiz Score for [{{ $activeCourseNo }}]</h4>
                        <form action="{{ route('portal.quiz.save') }}" method="POST" style="display:flex; gap:10px; flex-wrap:wrap;">
                            @csrf
                            <input type="hidden" name="course_no" value="{{ $activeCourseNo }}">
                            <select name="student_id" style="padding:8px; border-radius:4px; border:1px solid #ccc;" required>
                                <option value="">Select Target Student</option>
                                @foreach($students as $st) <option value="{{ $st->id }}">{{ $st->reg_no }} - {{ $st->name }}</option> @endforeach
                            </select>
                            <select name="quiz_name" style="padding:8px; border-radius:4px; border:1px solid #ccc;" required>
                                <option value="Quiz 1">Quiz 1</option><option value="Quiz 2">Quiz 2</option>
                                <option value="Quiz 3">Quiz 3</option><option value="Quiz 4">Quiz 4</option>
                            </select>
                            <input type="number" name="marks" placeholder="Obtained Marks" style="padding:8px; border-radius:4px; border:1px solid #ccc; width:140px;" min="0" required>
                            <button type="submit" style="background:#00a896; color:white; border:none; padding:8px 15px; border-radius:4px; font-weight:bold; cursor:pointer;">Record Marks</button>
                        </form>
                    </div>
                @endif

                <h4 style="color:#002f34; margin-top:20px;">Active Quiz Standings Table: {{ $activeCourseNo }}</h4>
                <table style="width:100%; border-collapse:collapse; margin-top:10px;">
                    <thead>
                        <tr style="background:#f8fafc;">
                            @if($user->role === 'faculty') <th style="padding:12px; border:1px solid #e2e8f0;">Student Reference ID</th> @endif
                            <th style="padding:12px; border:1px solid #e2e8f0;">Quiz Name</th>
                            <th style="padding:12px; border:1px solid #e2e8f0;">Total Marks</th>
                            <th style="padding:12px; border:1px solid #e2e8f0;">Obtained Marks</th>
                            @if($user->role === 'faculty') <th style="padding:12px; border:1px solid #e2e8f0;">Actions</th> @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quizzesList as $q)
                        <tr style="text-align:center;">
                            @if($user->role === 'faculty') <td style="padding:12px; border:1px solid #e2e8f0;">#{{ $q->student_id }}</td> @endif
                            <td style="padding:12px; border:1px solid #e2e8f0;"><span style="background:#e0f2fe; padding:4px 10px; border-radius:4px; font-weight:bold; color:#0369a1;">{{ $q->quiz_name }}</span></td>
                            <td style="padding:12px; border:1px solid #e2e8f0; color:#64748b; font-weight:500;">20</td>
                            <td style="padding:12px; border:1px solid #e2e8f0; font-size:15px;"><b>{{ $q->marks }}</b></td>
                            @if($user->role === 'faculty') <td style="padding:12px; border:1px solid #e2e8f0;"><a href="{{ route('portal.quiz.delete', $q->id) }}" style="color:#ef4444; font-weight:bold; text-decoration:none;">Purge</a></td> @endif
                        </tr>
                        @empty
                        <tr><td colspan="5" style="color:#64748b; padding:20px; text-align:center;">No active quiz scores recorded inside this subject section panel.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @elseif($tab === 'assignment_dashboard')
            <div class="panel-card" style="background:white; padding:25px; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.02);">
                <h3>¼ Core Subject Assignments Control Panel</h3>

                <div style="margin-bottom:20px; background:#f8fafc; padding:12px; border-radius:6px; border:1px solid #cbd5e1;">
                    <small style="font-weight:bold; color:#475569; display:block; margin-bottom:8px;">Select Subject Workspace Node:</small>
                    <div style="display:flex; flex-wrap:wrap; gap:8px;">
                        @foreach($coursesForCard as $c)
                            <a href="{{ route('portal.dashboard', ['tab' => 'assignment_dashboard', 'active_course' => $c['no']]) }}" class="subject-pill" style="background:{{ $activeCourseNo === $c['no'] ? '#00a896' : '#e2e8f0' }}; color:{{ $activeCourseNo === $c['no'] ? 'white' : '#334155' }};">{{ $c['no'] }}</a>
                        @endforeach
                    </div>
                </div>

                @if($user->role === 'faculty')
                    <div style="background:#fafafa; border:1px solid #cbd5e1; padding:15px; border-radius:6px; margin-bottom:20px;">
                        <h4 style="margin:0 0 10px 0;">Publish New Assignment Blueprint [{{ $activeCourseNo }}]</h4>
                        <form action="{{ route('portal.assignment.save') }}" method="POST" enctype="multipart/form-data" style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                            @csrf
                            <input type="hidden" name="course_no" value="{{ $activeCourseNo }}">
                            <input type="text" name="title" placeholder="Assignment Title (e.g., Assignment 1)" style="padding:8px; border-radius:4px; border:1px solid #ccc; grid-column:span 2;" required>
                            <textarea name="description" placeholder="Assignment operational tracking instructions text" style="padding:8px; border-radius:4px; border:1px solid #ccc; grid-column:span 2; height:60px;"></textarea>
                            <div style="grid-column: span 2; background: #fff; padding: 10px; border: 1px dashed #ccc; border-radius: 4px;">
                                <label style="font-size:12px; font-weight:bold; display:block; margin-bottom:4px;">Attach Question File (PDF, DOC, DOCX):</label>
                                <input type="file" name="question_file" accept=".pdf,.doc,.docx">
                            </div>
                            <div><label style="font-size:11px; font-weight:bold;">Timeline Launch Date</label><input type="datetime-local" name="start_date" style="width:100%; padding:6px; border-radius:4px; border:1px solid #ccc;" required></div>
                            <div><label style="font-size:11px; font-weight:bold;">Timeline Closing Date (Expiry Lock)</label><input type="datetime-local" name="last_date" style="width:100%; padding:6px; border-radius:4px; border:1px solid #ccc;" required></div>
                            <button type="submit" style="grid-column:span 2; background:#00a896; color:white; border:none; padding:10px; border-radius:4px; font-weight:bold; cursor:pointer;">Publish Blueprint</button>
                        </form>
                    </div>
                @endif

                <h4 style="color:#002f34; margin-top:20px;">Active Tasks Timeline Rows: {{ $activeCourseNo }}</h4>
                <div style="display:grid; gap:15px; margin-top:10px;">
                    @forelse($assignmentsList as $asg)
                        @php
                            $hasSubmitted = false;
                            if($user->role === 'student' && $currentStudent) {
                                $hasSubmitted = \App\Models\Assignment::where('student_id', $currentStudent->id)
                                                                     ->where('title', $asg->title)
                                                                     ->where('course_no', $asg->course_no)
                                                                     ->exists();
                            }
                        @endphp

                        @if($user->role === 'student' && $asg->student_id && $asg->student_id !== $currentStudent->id)
                            @continue
                        @endif

                        <div style="background:#fff; border:1px solid #cbd5e1; border-left:5px solid #0284c7; padding:20px; border-radius:6px; text-align:left;">
                            <div style="display:flex; justify-content:space-between; align-items:start;">
                                <div>
                                    <h4 style="margin:0 0 5px 0; color:#0f172a;">{{ $asg->title }}</h4>
                                    <p style="margin:0 0 10px 0; font-size:13px; color:#475569;">{{ $asg->description }}</p>
                                    <small style="color:#64748b; font-weight:bold; display:block; margin-bottom:8px;">⏰ Open Frame: {{ $asg->start_date }} to <span style="color:#ef4444;">{{ $asg->last_date }}</span></small>

                                    @if($asg->question_file)
                                        <a href="{{ asset($asg->question_file) }}" target="_blank" style="background:#f1f5f9; color:#0284c7; padding:5px 12px; border-radius:4px; font-size:12px; font-weight:bold; text-decoration:none; display:inline-flex; align-items:center; gap:5px; border:1px solid #cbd5e1;">📥 Download Assignment Question File</a>
                                    @endif
                                </div>
                                @if($user->role === 'faculty' && !$asg->student_id)
                                    <a href="{{ route('portal.assignment.delete', $asg->id) }}" style="color:#ef4444; font-weight:bold; font-size:13px; text-decoration:none;">Purge Blueprint</a>
                                @endif
                            </div>

                            <hr style="border:0; border-top:1px solid #e2e8f0; margin:15px 0;">

                            @if($user->role === 'student')
                                <div style="display:flex; align-items:center; justify-content:between; width:100%;">
                                    @if($hasSubmitted)
                                        <div style="background:#dcfce7; color:#16a34a; padding:10px 15px; border-radius:6px; font-weight:bold; font-size:13px; display:flex; align-items:center; gap:6px; width:100%;">
                                            <span>✓ Uploaded</span>
                                            <span style="font-weight:normal; color:#15803d; margin-left:auto;">(Submission Locked - Double uploads restricted)</span>
                                        </div>
                                    @else
                                        <form action="{{ route('portal.assignment.submit', $asg->id) }}" method="POST" enctype="multipart/form-data" style="display:flex; gap:10px; align-items:center; width:100%;">
                                            @csrf
                                            <label style="font-size:12px; font-weight:bold; color:#475569;">Upload File:</label>
                                            <input type="file" name="submission_file" accept=".pdf,.doc,.docx" style="font-size:12px;" required>
                                            <button type="submit" style="background:#00a896; color:white; padding:6px 15px; border:none; border-radius:4px; font-weight:bold; cursor:pointer; font-size:12px; margin-left:auto;">Submit Document</button>
                                        </form>
                                    @endif
                                </div>
                            @endif

                            @if($user->role === 'faculty' && $asg->student_id)
                                <div style="background:#f8fafc; padding:12px; border-radius:4px; border:1px solid #e2e8f0; font-size:13px; margin-top:10px;">
                                    <b>📌 Submission State Comment:</b> <span style="background:#bbf7d0; padding:2px 6px; border-radius:4px; font-weight:bold; color:#16a34a;">{{ $asg->description }}</span><br style="margin-bottom:5px;">
                                    <b>Student Row ID:</b> #{{ $asg->student_id }} |
                                    <a href="{{ asset($asg->file_path) }}" target="_blank" style="color:#0284c7; font-weight:bold; text-decoration:none;">Open Student Document Attachment</a>

                                    <div style="margin-top:8px; font-weight:bold;">
                                        Grading:
                                        @if($asg->marks !== null)
                                            <span style="color:#00a896;">{{ $asg->marks }} Marks Assigned</span>
                                        @else
                                            <span style="color:#eab308;">Awaiting Evaluation</span>
                                            <form action="{{ route('portal.assignment.grade', $asg->id) }}" method="POST" style="display:inline-flex; gap:5px; margin-left:15px;">
                                                @csrf
                                                <input type="number" name="marks" placeholder="Score" style="width:60px; padding:2px;" required>
                                                <button type="submit" style="background:#0284c7; color:white; border:none; padding:2px 8px; font-size:11px; cursor:pointer;">Commit</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div style="text-align:center; padding:25px; color:#64748b; border:1px dashed #cbd5e1; border-radius:4px;">No timeline assignment task modules are active in this workspace section.</div>
                    @endforelse
                </div>
            </div>

                @if($user->role === 'faculty')
                    <div style="background:#fafafa; border:1px solid #cbd5e1; padding:15px; border-radius:6px; margin-bottom:20px;">
                        <h4 style="margin:0 0 10px 0;">Publish New Assignment Blueprint [{{ $activeCourseNo }}]</h4>
                        <form action="{{ route('portal.assignment.save') }}" method="POST" style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                            @csrf
                            <input type="hidden" name="course_no" value="{{ $activeCourseNo }}">
                            <input type="text" name="title" placeholder="Assignment Title (e.g., Assignment 1: UML Specs)" style="padding:8px; border-radius:4px; border:1px solid #ccc; grid-column:span 2;" required>
                            <textarea name="description" placeholder="Assignment operational tracking instructions text" style="padding:8px; border-radius:4px; border:1px solid #ccc; grid-column:span 2; height:60px;"></textarea>
                            <div><label style="font-size:11px; font-weight:bold;">Timeline Launch Date</label><input type="datetime-local" name="start_date" style="width:100%; padding:6px; border-radius:4px; border:1px solid #ccc;" required></div>
                            <div><label style="font-size:11px; font-weight:bold;">Timeline Closing Date (Expiry Lock)</label><input type="datetime-local" name="last_date" style="width:100%; padding:6px; border-radius:4px; border:1px solid #ccc;" required></div>
                            <button type="submit" style="grid-column:span 2; background:#00a896; color:white; border:none; padding:10px; border-radius:4px; font-weight:bold; cursor:pointer;">Publish Blueprint</button>
                        </form>
                    </div>
                @endif

                <h4 style="color:#002f34; margin-top:20px;">Active Tasks Timeline Rows: {{ $activeCourseNo }}</h4>
                <div style="display:grid; gap:15px;">
                    @forelse($assignmentsList as $asg)
                        <div style="background:#fff; border:1px solid #cbd5e1; border-left:5px solid #0284c7; padding:20px; border-radius:6px; text-align:left;">
                            <div style="display:flex; justify-content:space-between; align-items:start;">
                                <div>
                                    <h4 style="margin:0 0 5px 0; color:#0f172a;">{{ $asg->title }}</h4>
                                    <p style="margin:0 0 10px 0; font-size:13px; color:#475569;">{{ $asg->description }}</p>
                                    <small style="color:#64748b; font-weight:bold; display:block;">⏰ Open Frame: {{ $asg->start_date }} to <span style="color:#ef4444;">{{ $asg->last_date }}</span></small>
                                </div>
                                @if($user->role === 'faculty' && !$asg->student_id)
                                    <a href="{{ route('portal.assignment.delete', $asg->id) }}" style="color:#ef4444; font-weight:bold; font-size:13px; text-decoration:none;">Purge Blueprint</a>
                                @endif
                            </div>

                            @if($user->role === 'student' && !$asg->file_path)
                                <hr style="border:0; border-top:1px solid #e2e8f0; margin:15px 0;">
                                <form action="{{ route('portal.assignment.submit', $asg->id) }}" method="POST" enctype="multipart/form-data" style="display:flex; gap:10px; align-items:center;">
                                    @csrf
                                    <label style="font-size:12px; font-weight:bold; color:#475569;">Upload Submission (PDF, DOC, DOCX only):</label>
                                    <input type="file" name="submission_file" accept=".pdf,.doc,.docx" style="font-size:12px;" required>
                                    <button type="submit" style="background:#00a896; color:white; padding:6px 15px; border:none; border-radius:4px; font-weight:bold; cursor:pointer; font-size:12px;">Submit File</button>
                                </form>
                            @endif

                            @if($asg->student_id)
                                <div style="margin-top:15px; background:#f8fafc; padding:10px; border-radius:4px; border:1px solid #e2e8f0; font-size:13px;">
                                    <b>📌 Submission Verified for Student Row Node:</b> #{{ $asg->student_id }}<br>
                                    <a href="{{ asset($asg->file_path) }}" target="_blank" style="color:#0284c7; font-weight:bold; text-decoration:none; display:inline-block; margin-top:5px;">Open Submitted Asset Attachment</a>

                                    <div style="margin-top:8px; font-weight:bold;">
                                        Awarded Score Standings:
                                        @if($asg->marks !== null)
                                            <span style="color:#00a896;">{{ $asg->marks }} Marks</span>
                                        @else
                                            <span style="color:#eab308;">Awaiting Evaluation Metric</span>
                                            @if($user->role === 'faculty')
                                                <form action="{{ route('portal.assignment.grade', $asg->id) }}" method="POST" style="display:inline-flex; gap:5px; margin-left:15px;">
                                                    @csrf
                                                    <input type="number" name="marks" placeholder="Score" style="width:60px; padding:2px;" required>
                                                    <button type="submit" style="background:#0284c7; color:white; border:none; padding:2px 8px; font-size:11px; cursor:pointer;">Commit</button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div style="text-align:center; padding:20px; color:#64748b; border:1px dashed #cbd5e1; border-radius:4px;">No timeline task models are currently active or visible inside this workspace window segment.</div>
                    @endforelse
                </div>
            </div>

        @elseif($tab === 'result')
            @if($user->role === 'faculty')
                <div class="panel-card" style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); margin-bottom: 25px;">
                    <h3>3- Analytical Results Dashboard Panel</h3>
                    <small style="color:#64748b; font-weight:bold; display:block; margin-bottom:10px;">Select Target Row node to generate report sheet:</small>
                    <div style="display:flex; flex-wrap:wrap; gap:8px;">
                        @foreach($students as $s)
                            <a href="{{ route('portal.dashboard', ['tab' => 'result', 'result_student_id' => $s->id]) }}" style="background:#0f766e; color:white; padding:6px 12px; border-radius:4px; text-decoration:none; font-size:12px; font-weight:bold;">Compile {{ $s->reg_no }}</a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="panel-card" style="border-left:4px solid #1e3a8a; background:#fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); margin-bottom: 25px;">
                    <h4 style="margin-top:0; color:#1e3a8a;">Student Transcript Query Verification Mask</h4>
                    <form action="{{ route('portal.dashboard') }}" method="GET" style="display:flex; gap:12px; margin-top:12px;">
                        <input type="hidden" name="tab" value="result">
                        <input type="email" name="search_email" value="{{ request('search_email') }}" placeholder="Your Registered Account Email" style="flex:1; padding:10px; border:1px solid #cbd5e1; border-radius:4px;" required>
                        <input type="text" name="search_reg" value="{{ request('search_reg') }}" placeholder="Your Registration ID (e.g., FA23-BSE-044)" style="flex:1; padding:10px; border:1px solid #cbd5e1; border-radius:4px;" required>
                        <button type="submit" style="background:#1e3a8a; color:white; padding:10px 20px; border:none; border-radius:4px; font-weight:bold; cursor:pointer;">Query Target Matrix</button>
                    </form>
                </div>
            @endif

            @if($studentForResult && !empty($processedMatrix))
                <div style="margin-top: 20px; padding-bottom: 30px;">
                    <div id="transcript-capture-node" style="border:1px solid #334155; padding:40px; background:white; position:relative; box-sizing: border-box; width: 100%; max-width: 760px; margin: 0 auto; overflow: visible;">
                        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:300px; height:300px; background: url('https://www.comsats.edu.pk/images/logo.png') no-repeat center/contain; opacity:0.03; pointer-events:none;"></div>
                        <div style="display:flex; align-items:center; gap:15px; border-bottom:2px solid #002f34; padding-bottom:15px; margin-bottom:25px;">
                            <img src="https://www.comsats.edu.pk/images/logo.png" style="width:52px;">
                            <div>
                                <h3 style="margin:0; color:#002f34; text-transform:uppercase; font-size: 22px; font-family:'Segoe UI', Arial, sans-serif; letter-spacing: 0.5px;">COMSATS University Islamabad</h3>
                                <small style="color:#64748b; font-weight:bold; font-size:12px; text-transform: uppercase; letter-spacing: 0.5px;">Official Terminal Examination Grade Report Sheet</small>
                            </div>
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; font-size:14px; margin-bottom:25px; background:#f8fafc; padding:15px; border-radius:6px; border:1px solid #e2e8f0; font-family:'Segoe UI', sans-serif;">
                            <div><b>Student Name:</b> <span style="color:#334155;">{{ $studentForResult->name }}</span></div>
                            <div><b>Registration ID:</b> <span style="color:#334155;">{{ $studentForResult->reg_no }}</span></div>
                            <div><b>Plan Framework:</b> <span style="color:#334155;">BS {{ $studentForResult->department }}</span></div>
                            <div><b>Academic Node:</b> <span style="color:#334155;">Semester {{ $studentForResult->semester }}</span></div>
                        </div>
                        <table style="width:100%; border-collapse:collapse; margin-bottom:25px; font-family:'Segoe UI', sans-serif;">
                            <thead>
                                <tr>
                                    <th style="border:1px solid #cbd5e1; padding:12px; background:#f1f5f9; color:#334155; font-size:13px; font-weight:bold; text-align:center; width:15%;">Code</th>
                                    <th style="border:1px solid #cbd5e1; padding:12px; background:#f1f5f9; color:#334155; font-size:13px; font-weight:bold; text-align:left; width:45%;">Subject Course Title Description</th>
                                    <th style="border:1px solid #cbd5e1; padding:12px; background:#f1f5f9; color:#334155; font-size:13px; font-weight:bold; text-align:center; width:10%;">Credits</th>
                                    <th style="border:1px solid #cbd5e1; padding:12px; background:#f1f5f9; color:#334155; font-size:13px; font-weight:bold; text-align:center; width:10%;">Score</th>
                                    <th style="border:1px solid #cbd5e1; padding:12px; background:#f1f5f9; color:#334155; font-size:13px; font-weight:bold; text-align:center; width:10%;">Grade</th>
                                    <th style="border:1px solid #cbd5e1; padding:12px; background:#f1f5f9; color:#334155; font-size:13px; font-weight:bold; text-align:center; width:10%;">G.P.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($processedMatrix as $row)
                                <tr style="page-break-inside: avoid;">
                                    <td style="border:1px solid #cbd5e1; padding:11px; text-align:center; font-size:13px;"><b>{{ $row['no'] }}</b></td>
                                    <td style="border:1px solid #cbd5e1; padding:11px; text-align:left; font-size:13px; color:#334155;">{{ $row['title'] }}</td>
                                    <td style="border:1px solid #cbd5e1; padding:11px; text-align:center; font-size:13px;">{{ $row['cr'] }}</td>
                                    <td style="border:1px solid #cbd5e1; padding:11px; text-align:center; font-size:13px;">{{ $row['marks'] }}</td>
                                    <td style="border:1px solid #cbd5e1; padding:11px; text-align:center; font-size:13px;"><b>{{ $row['lg'] }}</b></td>
                                    <td style="border:1px solid #cbd5e1; padding:11px; text-align:center; font-size:13px; font-weight:500;">{{ number_format($row['gp'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div style="page-break-inside: avoid; margin-top:25px; break-inside: avoid;">
                            <div style="background:#f0fdfa; padding:16px; border-left:4px solid #00a896; border-radius:0 4px 4px 0; font-size:13px; color:#0f766e; text-align:left; margin-bottom: 20px; font-family:'Segoe UI', sans-serif; line-height: 1.5;">
                                <b style="font-size:14px; display:block; margin-bottom:6px; color:#0d9488;">🤖 Predictive AI Advisor Insight:</b>{{ $aiAdvice }}
                            </div>
                            <h3 style="margin:0; padding-top:5px; color:#002f34; text-align:left; font-size:19px; font-family:'Segoe UI', sans-serif;">Calculated Cumulative CGPA Metric Score: <span style="color:#00a896; font-weight: bold; border-bottom: 2px double #00a896; padding-bottom: 2px;">{{ number_format($cgpa, 2) }}</span></h3>
                        </div>
                    </div>
                    <div style="text-align:right; margin-top:20px; max-width:760px; margin-left:auto; margin-right:auto;"><button onclick="triggerPdfExport()" style="background:#0284c7; color:white; border:none; padding:12px 25px; border-radius:6px; font-weight:bold; cursor:pointer; font-size:14px; box-shadow:0 4px 12px rgba(2,132,199,0.25); transition: 0.2s;">Download Official PDF Report</button></div>
                </div>
                <script>
                    function triggerPdfExport() {
                        const element = document.getElementById('transcript-capture-node');
                        const options = {
                            margin:       [0.4, 0.4, 0.4, 0.4], filename:     'CUI_Transcript_{{ $studentForResult->reg_no }}.pdf', image:        { type: 'jpeg', quality: 0.98 },
                            html2canvas:  { scale: 2, useCORS: true, logging: false, backgroundColor: '#ffffff', scrollY: 0, scrollX: 0 },
                            jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }, pagebreak:    { mode: ['avoid-all', 'css', 'legacy'] }
                        };
                        html2pdf().set(options).from(element).save();
                    }
                </script>
            @endif
        @endif
    </div>
</div>
@endsection
