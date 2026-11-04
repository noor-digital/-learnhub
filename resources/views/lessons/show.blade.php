@extends('layouts.app')
@section('title', $lesson->title)
@section('content')
<style>
.ls-wrap{display:grid;grid-template-columns:1fr 300px;height:calc(100vh - var(--nav-h));overflow:hidden}
@media(max-width:991px){.ls-wrap{grid-template-columns:1fr;height:auto;overflow:visible}
    .ls-aside{display:none}.ls-aside.mob-show{display:flex;flex-direction:column}}
.ls-main{overflow-y:auto;background:#0f0f0f;display:flex;flex-direction:column}
.ls-aside{overflow-y:auto;background:#fff;border-left:1px solid #e8e6e0;
    display:flex;flex-direction:column}
.aside-hd{background:var(--navy);padding:.8rem 1rem;color:#fff;font-size:.84rem;
    font-weight:600;display:flex;justify-content:space-between;flex-shrink:0}
.aside-prog{padding:.75rem 1rem;border-bottom:1px solid #f0efe9;flex-shrink:0}
.l-item-s{display:flex;align-items:flex-start;gap:9px;padding:.6rem 1rem;
    border-bottom:1px solid #f0efe9;font-size:.8rem;cursor:pointer;
    text-decoration:none;color:#333;transition:background .12s}
.l-item-s:hover{background:#f8f7f4}
.l-item-s.active-l{background:#eeedfe;color:var(--brand);font-weight:600}
.l-item-s.done-l .l-ic{color:#198754}
.l-ic{font-size:.85rem;flex-shrink:0;margin-top:1px}
.video-box{position:relative;padding-bottom:56.25%;background:#000;flex-shrink:0}
.video-box iframe,.video-box video{position:absolute;inset:0;width:100%;height:100%;border:0}
.video-no{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;
    flex-direction:column;color:#555;gap:8px}
.ls-bottom{background:#1a1a2e;padding:.85rem 1.1rem;
    display:flex;align-items:center;justify-content:space-between;
    gap:8px;flex-shrink:0;flex-wrap:wrap}
.ls-nav-btn{display:flex;align-items:center;gap:6px;padding:.5rem 1rem;
    border-radius:8px;border:1px solid #2d2d4e;color:#c0bfe0;
    text-decoration:none;font-size:.83rem;transition:all .15s}
.ls-nav-btn:hover{background:#2d2d4e;color:#fff}
.ls-nav-btn.next-b{background:var(--brand);border-color:var(--brand);color:#fff}
.ls-nav-btn.next-b:hover{background:var(--brand-dk)}
.btn-done{display:flex;align-items:center;gap:6px;padding:.48rem .95rem;
    border-radius:8px;border:1px solid #3d3d5e;color:#c0bfe0;
    background:transparent;cursor:pointer;font-size:.82rem;transition:all .15s}
.btn-done.done{background:#198754;border-color:#198754;color:#fff}
.btn-done:hover{background:#2d2d4e;color:#fff}
.toggle-mob{display:none;position:fixed;bottom:1rem;right:1rem;z-index:50;
    background:var(--brand);color:#fff;border:none;border-radius:50%;
    width:48px;height:48px;font-size:1.2rem;box-shadow:0 4px 16px rgba(108,99,255,.4)}
@media(max-width:991px){.toggle-mob{display:flex;align-items:center;justify-content:center}}
</style>

<div class="ls-wrap">

    <!-- Main -->
    <div class="ls-main">
        <!-- Vidéo -->
        <div class="video-box">
            @if($lesson->embed_url)
                <iframe src="{{ $lesson->embed_url }}"
                        allow="accelerometer;autoplay;clipboard-write;encrypted-media;
                               gyroscope;picture-in-picture"
                        allowfullscreen></iframe>
            @else
                <div class="video-no">
                    <i class="bi bi-camera-video-off" style="font-size:3rem"></i>
                    <span style="font-size:.85rem">Aucune vidéo pour cette leçon</span>
                </div>
            @endif
        </div>

        <!-- Infos leçon + actions -->
        <div style="background:#1a1a2e;padding:1rem 1.1rem;flex:1">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                <div>
                    <div style="color:#9b9bc4;font-size:.75rem;margin-bottom:4px">
                        Leçon {{ $lessons->search(fn($l)=>$l->id===$lesson->id)+1 }}
                        / {{ $lessons->count() }} —
                        <a href="{{ route('courses.show',$course) }}"
                           style="color:#6c63ff;text-decoration:none">
                            {{ Str::limit($course->title,40) }}
                        </a>
                    </div>
                    <h1 style="color:#fff;font-size:1.15rem;font-weight:600;margin:0">
                        {{ $lesson->title }}
                    </h1>
                </div>

                @auth
                @if(auth()->user()->isEnrolledIn($course))
                @php $done=in_array($lesson->id,$completedIds) @endphp
                <form method="POST" action="{{ route('lessons.complete',$lesson) }}">
                    @csrf
                    <button type="submit"
                            class="btn-done {{ $done?'done':'' }}">
                        <i class="bi bi-{{ $done?'check-circle-fill':'circle' }}"></i>
                        {{ $done?'Terminée':'Marquer terminée' }}
                    </button>
                </form>
                @endif
                @endauth
            </div>

            @if($lesson->duration)
            <div style="color:#5f5e8a;font-size:.77rem">
                <i class="bi bi-clock me-1"></i>{{ $lesson->duration }} min
            </div>
            @endif
        </div>

        <!-- Navigation entre leçons -->
        @php
            $idx=$lessons->search(fn($l)=>$l->id===$lesson->id);
            $prev=$idx>0?$lessons[$idx-1]:null;
            $next=$idx<$lessons->count()-1?$lessons[$idx+1]:null;
        @endphp
        <div class="ls-bottom">
            @if($prev)
            <a href="{{ route('lessons.show',[$course,$prev]) }}" class="ls-nav-btn">
                <i class="bi bi-arrow-left"></i>Précédente
            </a>
            @else<div></div>@endif

            @if($next)
            <a href="{{ route('lessons.show',[$course,$next]) }}"
               class="ls-nav-btn next-b" id="btn-next">
                Suivante<i class="bi bi-arrow-right"></i>
            </a>
            @else
            <a href="{{ route('courses.show',$course) }}"
               class="ls-nav-btn next-b">
                <i class="bi bi-trophy me-1"></i>Terminer
            </a>
            @endif
        </div>
    </div>

    <!-- Sidebar leçons -->
    <div class="ls-aside" id="aside">
        <div class="aside-hd">
            <span>Programme</span>
            <span style="color:#9b9bc4;font-weight:400;font-size:.76rem">
                {{ $lessons->count() }} leçons
            </span>
        </div>

        <!-- Progression -->
        @auth
        @if(auth()->user()->isEnrolledIn($course))
        @php
            $prog=auth()->user()->enrolledCourses()
                ->where('course_id',$course->id)->first()?->pivot->progress??0;
        @endphp
        <div class="aside-prog">
            <div class="d-flex justify-content-between mb-1" style="font-size:.75rem">
                <span class="text-muted">Progression</span>
                <span class="fw-semibold" style="color:{{ $prog==100?'#198754':'var(--brand)' }}">
                    {{ $prog }}%
                </span>
            </div>
            <div class="prog-wrap">
                <div class="prog-fill {{ $prog==100?'done':'' }}"
                     style="width:{{ $prog }}%"></div>
            </div>
        </div>
        @endif
        @endauth

        <!-- Liste leçons -->
        <div style="flex:1;overflow-y:auto">
            @foreach($lessons as $item)
            @php
                $isActive=$item->id===$lesson->id;
                $isDone=in_array($item->id,$completedIds);
            @endphp
            <a href="{{ route('lessons.show',[$course,$item]) }}"
               class="l-item-s {{ $isActive?'active-l':'' }} {{ $isDone?'done-l':'' }}">
                <span class="l-ic">
                    @if($isDone)
                        <i class="bi bi-check-circle-fill" style="color:#198754"></i>
                    @elseif($isActive)
                        <i class="bi bi-play-circle-fill" style="color:var(--brand)"></i>
                    @else
                        <i class="bi bi-circle" style="color:#d3d1c7"></i>
                    @endif
                </span>
                <div style="flex:1;line-height:1.3">
                    <div>{{ $item->title }}</div>
                    @if($item->duration)
                    <div style="font-size:.7rem;color:#aaa;margin-top:1px">
                        {{ $item->duration }} min
                    </div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Bouton mobile pour toggle sidebar -->
<button class="toggle-mob" onclick="toggleAside()" title="Programme">
    <i class="bi bi-list-check"></i>
</button>

<script>
function toggleAside(){
    document.getElementById('aside').classList.toggle('mob-show');
}

// Auto-lecture leçon suivante après 3s si la vidéo est terminée
@if($next)
const nextUrl='{{ route("lessons.show",[$course,$next]) }}';
const iframe=document.querySelector('iframe');
if(iframe){
    // Pour YouTube via postMessage
    window.addEventListener('message',e=>{
        try{
            const d=JSON.parse(e.data);
            if(d.event==='onStateChange'&&d.info===0){
                setTimeout(()=>window.location.href=nextUrl,2500);
            }
        }catch(e){}
    });
}
@endif
</script>
@endsection