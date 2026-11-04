@extends('layouts.app')
@section('title','Mon espace')
@section('content')
<div class="container-fluid py-4 px-4" style="max-width:1100px;margin:0 auto">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h4 fw-bold mb-0" style="color:#1a1a2e">
                <i class="bi bi-mortarboard me-2" style="color:var(--brand)"></i>
                Mon espace
            </h1>
            <p class="text-muted small mb-0">
                Bonjour <strong>{{ auth()->user()->name }}</strong> 🚀
            </p>
        </div>
        <a href="{{ route('courses.index') }}" class="btn fw-semibold px-4"
           style="background:var(--brand);color:#fff;border-radius:9px;border:none">
            <i class="bi bi-search me-1"></i>Découvrir des cours
        </a>
    </div>

    @if($enrolledCourses->isEmpty())
    <div class="text-center py-5 my-3">
        <i class="bi bi-journal-x" style="font-size:4rem;color:#d3d1c7"></i>
        <h2 class="h5 fw-bold mt-3 mb-2">Aucun cours suivi</h2>
        <p class="text-muted small mb-4" style="max-width:340px;margin:0 auto">
            Parcourez le catalogue et inscrivez-vous à votre premier cours.
        </p>
        <a href="{{ route('courses.index') }}" class="btn fw-semibold px-5"
           style="background:var(--brand);color:#fff;border-radius:9px;border:none">
            Voir le catalogue
        </a>
    </div>
    @else

    <!-- Stats -->
    <div class="row g-3 mb-4">
        @php
        $sc=[
            ['lbl'=>'Cours suivis','val'=>$stats['total_enrolled'],
             'ic'=>'collection-play','bg'=>'#eeedfe','c'=>'var(--brand)'],
            ['lbl'=>'Terminés','val'=>$stats['completed'],
             'ic'=>'trophy','bg'=>'#e1f5ee','c'=>'#0f6e56'],
            ['lbl'=>'En cours','val'=>$stats['in_progress'],
             'ic'=>'play-circle','bg'=>'#faeeda','c'=>'#854f0b'],
            ['lbl'=>'Progression moy.','val'=>$stats['avg_progress'].'%',
             'ic'=>'graph-up','bg'=>'#faece7','c'=>'#993c1d'],
        ];
        @endphp
        @foreach($sc as $s)
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-ic" style="background:{{ $s['bg'] }}">
                    <i class="bi bi-{{ $s['ic'] }}" style="color:{{ $s['c'] }}"></i>
                </div>
                <div>
                    <div class="stat-lbl">{{ $s['lbl'] }}</div>
                    <div class="stat-val">{{ $s['val'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Mes cours -->
    <h2 class="h6 fw-bold mb-3" style="color:#1a1a2e">
        <i class="bi bi-journals me-2" style="color:var(--brand)"></i>Mes cours
    </h2>
    <div class="row g-3 g-md-4 mb-4">
        @foreach($enrolledCourses as $idx => $course)
        <div class="col-6 col-lg-3 fade-up" style="animation-delay:{{ ($idx%4)*60 }}ms">
            <div class="h-100 rounded-3 overflow-hidden"
                 style="background:#fff;border:1px solid #e8e6e0;
                        transition:transform .2s,box-shadow .2s"
                 onmouseover="this.style.transform='translateY(-4px)';
                              this.style.boxShadow='0 12px 32px rgba(0,0,0,.08)'"
                 onmouseout="this.style.transform='';this.style.boxShadow=''">

                <!-- Thumbnail -->
                @if($course->thumbnail)
                <img src="{{ Storage::url($course->thumbnail) }}"
                     style="width:100%;height:140px;object-fit:cover"
                     alt="{{ $course->title }}">
                @else
                <div style="width:100%;height:140px;background:#eeedfe;
                            display:flex;align-items:center;justify-content:center;
                            font-size:2.2rem;color:var(--brand)">
                    <i class="bi bi-play-circle"></i>
                </div>
                @endif

                <div style="padding:12px">
                    <span class="badge mb-2"
                          style="background:#eeedfe;color:#534ab7;font-size:.7rem">
                        {{ $course->category->name }}
                    </span>
                    <div class="fw-semibold mb-1"
                         style="font-size:.86rem;color:#1a1a2e;
                                display:-webkit-box;-webkit-line-clamp:2;
                                -webkit-box-orient:vertical;overflow:hidden">
                        {{ $course->title }}
                    </div>
                    <div class="text-muted mb-2" style="font-size:.74rem">
                        <i class="bi bi-person me-1"></i>{{ $course->formateur->name }}
                    </div>

                    <!-- Progression -->
                    <div class="d-flex justify-content-between mb-1"
                         style="font-size:.73rem">
                        <span class="text-muted">Progression</span>
                        <span class="fw-semibold"
                              style="color:{{ $course->progress==100?'#198754':'var(--brand)' }}">
                            {{ $course->progress }}%
                        </span>
                    </div>
                    <div class="prog-wrap mb-2">
                        <div class="prog-fill {{ $course->progress==100?'done':'' }}"
                             style="width:{{ $course->progress }}%"></div>
                    </div>

                    @if($course->progress==100)
                    <span class="badge mb-2"
                          style="background:#e1f5ee;color:#0f6e56;font-size:.7rem">
                        <i class="bi bi-check-circle-fill me-1"></i>Terminé
                    </span>
                    @endif

                    <!-- Actions -->
                    <div class="d-flex gap-2 mt-1">
                        @if($course->progress>=100)
                        <a href="{{ route('certificate.generate',$course) }}"
                           class="btn btn-sm fw-semibold flex-fill"
                           style="background:#198754;color:#fff;border-radius:7px;
                                  border:none;font-size:.78rem">
                            <i class="bi bi-award me-1"></i>Certificat
                        </a>
                        @elseif($course->lessons_count>0)
                        <a href="{{ route('lessons.show',[$course,$course->lessons->first()]) }}"
                           class="btn btn-sm fw-semibold flex-fill"
                           style="background:var(--brand);color:#fff;border-radius:7px;
                                  border:none;font-size:.78rem">
                            <i class="bi bi-play-fill me-1"></i>Continuer
                        </a>
                        @else
                        <a href="{{ route('courses.show',$course) }}"
                           class="btn btn-sm btn-outline-secondary flex-fill"
                           style="border-radius:7px;font-size:.78rem">Voir</a>
                        @endif
                        <a href="{{ route('courses.show',$course) }}"
                           class="btn btn-sm btn-outline-secondary"
                           style="border-radius:7px;font-size:.78rem;padding:.25rem .5rem">
                            <i class="bi bi-info-circle"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Activité récente -->
    @if($recentActivity->isNotEmpty())
    <h2 class="h6 fw-bold mb-3" style="color:#1a1a2e">
        <i class="bi bi-clock-history me-2" style="color:var(--brand)"></i>Activité récente
    </h2>
    <div class="card border-0 shadow-sm" style="border-radius:12px">
        <div class="card-body p-0">
            @foreach($recentActivity as $lesson)
            <div class="d-flex align-items-center gap-3 px-4 py-3
                        {{ !$loop->last?'border-bottom':'' }}"
                 style="font-size:.83rem">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:34px;height:34px;background:#e1f5ee;flex-shrink:0">
                    <i class="bi bi-check-circle-fill" style="color:#198754;font-size:.88rem"></i>
                </div>
                <div style="flex:1">
                    <div class="fw-semibold" style="color:#1a1a2e">{{ $lesson->title }}</div>
                    <div class="text-muted" style="font-size:.75rem">
                        {{ $lesson->course->title }}
                    </div>
                </div>
                <div class="text-muted" style="font-size:.73rem">
                    {{ \Carbon\Carbon::parse($lesson->pivot->completed_at)->diffForHumans() }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endif
</div>
@endsection