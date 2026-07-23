@if(session('success'))
<div class="alert alert-success" id="flash-alert">
    <i class="fa-solid fa-circle-check"></i>
    <span>{{ session('success') }}</span>
    <button onclick="this.parentElement.remove()" class="alert-close"><i class="fa-solid fa-xmark"></i></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-error" id="flash-alert">
    <i class="fa-solid fa-circle-exclamation"></i>
    <span>{{ session('error') }}</span>
    <button onclick="this.parentElement.remove()" class="alert-close"><i class="fa-solid fa-xmark"></i></button>
</div>
@endif
@if($errors->any())
<div class="alert alert-error" id="flash-alert">
    <i class="fa-solid fa-circle-exclamation"></i>
    <ul style="margin:0;padding-left:1rem;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button onclick="this.parentElement.remove()" class="alert-close"><i class="fa-solid fa-xmark"></i></button>
</div>
@endif
<script>
    setTimeout(() => { const a = document.getElementById('flash-alert'); if(a) a.remove(); }, 5000);
</script>
