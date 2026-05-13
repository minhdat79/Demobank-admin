@extends('layouts.admin')
@section('title', $item->exists? 'Sửa tin tuyển dụng':'Đăng tin tuyển dụng')

@section('content')
<form id="jobForm" method="POST"
      action="{{ $item->exists? route('admin.jobs.update',$item) : route('admin.jobs.store') }}"
      class="max-w-5xl space-y-6" enctype="multipart/form-data">
  @csrf
  @if($item->exists) @method('PUT') @endif

  @if ($errors->any())
    <div class="alert alert-danger">
      <div class="fw-bold mb-1">Không lưu được. Vui lòng kiểm tra:</div>
      <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
  @endif
  @if (session('ok'))   <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div> @endif

  <div class="bg-white rounded-2xl border p-6 space-y-6">
 
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Chức danh / Vị trí <span class="text-rose-600">*</span></label>
      <input name="title" value="{{ old('title',$item->title) }}" required
             class="w-full rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500" />
      @error('title')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>

  
    <div class="grid md:grid-cols-4 gap-4">
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Phòng ban</label>
        <input name="department" value="{{ old('department',$item->department) }}" class="w-full rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500" />
      </div>
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Địa điểm</label>
        <input name="location" value="{{ old('location',$item->location) }}" class="w-full rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500" />
      </div>
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Hình thức</label>
        <input name="employment_type" value="{{ old('employment_type',$item->employment_type) }}" class="w-full rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500" />
      </div>
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Trạng thái</label>
        <select name="status" class="w-full rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500">
          @php $st = old('status',$item->status ?? 'open'); @endphp
          <option value="open" {{ $st==='open' ? 'selected':'' }}>Mở</option>
          <option value="closed" {{ $st==='closed' ? 'selected':'' }}>Đóng</option>
        </select>
        @error('status')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
      </div>
    </div>

 
    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Mức lương từ</label>
        <input type="number" name="salary_min" value="{{ old('salary_min',$item->salary_min) }}" class="w-full rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500" />
      </div>
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Đến</label>
        <input type="number" name="salary_max" value="{{ old('salary_max',$item->salary_max) }}" class="w-full rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500" />
      </div>
    </div>


    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Ngày đăng</label>
        <input type="date" name="publish_date" value="{{ old('publish_date', optional($item->publish_date)->format('Y-m-d')) }}" class="w-full rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500" />
      </div>
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Hạn nộp</label>
        <input type="date" name="close_date" value="{{ old('close_date', optional($item->close_date)->format('Y-m-d')) }}" class="w-full rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500" />
      </div>
    </div>


    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Link ứng tuyển</label>
      <input name="apply_url" value="{{ old('apply_url',$item->apply_url) }}" class="w-full rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500" />
    </div>

    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Ảnh banner (tuỳ chọn)</label>
      <input type="file" name="banner_image" accept="image/*"
             class="w-full rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500"
             onchange="previewBanner(this)">
      <div class="text-xs text-slate-500 mt-1">Tối đa 5MB, khuyến nghị 16:9.</div>
      <div class="mt-2">
        <img id="bannerPreview" src="{{ $item->banner_url ?? '' }}" alt=""
             style="max-width:100%;height:auto;border-radius:10px;{{ $item->banner_url ? '' : 'display:none' }}">
      </div>
      @error('banner_image')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>

  
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Tóm tắt</label>
      <textarea id="summary" name="summary" class="hidden">{{ old('summary',$item->summary) }}</textarea>
      <div id="summary__holder"></div>
    </div>


    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Mô tả / Yêu cầu / Quyền lợi</label>
      <textarea id="description" name="description" class="hidden">{{ old('description',$item->description) }}</textarea>
      <div id="description__holder"></div>
    </div>


    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">SEO Title</label>
        <input name="seo_title" value="{{ old('seo_title',$item->seo_title) }}" class="w-full rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500" />
      </div>
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">SEO Description</label>
        <input name="seo_description" value="{{ old('seo_description',$item->seo_description) }}" class="w-full rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500" />
      </div>
    </div>


    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="is_published" value="1" {{ old('is_published',$item->is_published) ? 'checked':'' }}
             class="rounded border-slate-300 text-sky-600 focus:ring-sky-500" />
      <span class="text-sm text-slate-700">Hiển thị công khai</span>
    </label>

    <div class="pt-2">
      <button id="submitBtn" type="submit" class="btn btn-primary">
        {{ $item->exists? 'Cập nhật' : 'Đăng tin' }}
      </button>
    </div>
  </div>
</form>

<script>
function previewBanner(input){
  const img=document.getElementById('bannerPreview');
  if(input.files && input.files[0]){
    const r=new FileReader();
    r.onload=e=>{img.src=e.target.result;img.style.display='block';};
    r.readAsDataURL(input.files[0]);
  }
}
// Submit an toàn
(function(){
  const f=document.getElementById('jobForm'), b=document.getElementById('submitBtn');
  if(!f||!b) return;
  f.addEventListener('submit',()=>{ b.disabled=true; b.textContent='Đang lưu…'; });
  window.addEventListener('pageshow',()=>{ b.disabled=false; b.textContent='{{ $item->exists? 'Cập nhật' : 'Đăng tin' }}'; });
})();
</script>


<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<style>[class*="ck-powered-by"]{display:none !important;}</style>
<script>
function bootEditor(textareaId, holderId, mini){
  const ta=document.getElementById(textareaId), holder=document.getElementById(holderId), init=ta.value||'';
  return ClassicEditor.create(holder,{
    placeholder:'Nhập nội dung…',
    toolbar:{items: mini
      ? ['bold','italic','link','bulletedList','numberedList','|','imageUpload','undo','redo']
      : ['heading','|','bold','italic','underline','link','bulletedList','numberedList','blockQuote','insertTable','|','imageUpload','mediaEmbed','|','undo','redo']},
    simpleUpload:{uploadUrl:'{{ route('admin.uploads.ckeditor') }}',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}},
    image:{toolbar:['imageStyle:inline','imageStyle:block','imageStyle:side','|','imageTextAlternative'],styles:['inline','block','side']}
  }).then(ed=>{
    ed.setData(init);
    document.getElementById('jobForm').addEventListener('submit',()=>{ ta.value=ed.getData(); });
  }).catch(console.error);
}
bootEditor('summary','summary__holder',true);
bootEditor('description','description__holder',false);
</script>
@endsection
