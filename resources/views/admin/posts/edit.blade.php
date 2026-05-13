@extends('layouts.admin')
@section('title','Chỉnh sửa bài viết')

@section('content')
<form method="POST" action="{{ route('admin.posts.update',$post) }}" enctype="multipart/form-data" class="max-w-3xl">
  @csrf @method('PUT')
  <div class="bg-white/80 backdrop-blur rounded-2xl border border-sky-100 shadow-sm p-6 space-y-5">
    <div class="text-sm text-slate-500">Tác giả: <b>{{ $post->user->name ?? 'Ẩn danh' }}</b></div>

    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Tiêu đề</label>
      <input name="title" value="{{ old('title',$post->title) }}"
             class="w-full rounded-xl border-sky-200 focus:border-sky-500 focus:ring-sky-500" required>
      @error('title')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>

    {{-- CKEditor --}}
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Nội dung</label>
      <textarea id="editor-content" name="content" rows="10" class="hidden">{{ old('content',$post->content) }}</textarea>
      <div id="editor-content__holder" class="min-h-[520px]"></div>
      @error('content')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>

    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Ảnh bìa</label>
      <input type="file" name="image" accept="image/*"
             class="block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl
                    file:border-0 file:text-sm file:font-semibold
                    file:bg-sky-100 file:text-sky-800 hover:file:bg-sky-200">
      @error('image')<div class="text-rose-600 text-sm mt-1">{{ $message }}</div>@enderror

      <div class="mt-3 grid gap-3 sm:grid-cols-2">
        @if($post->image_path)
          <div>
            <div class="text-xs text-slate-500 mb-1">Ảnh hiện tại</div>
            <img src="{{ asset('storage/'.$post->image_path) }}" class="rounded-xl max-h-64 border border-sky-100">
          </div>
        @endif
        <div>
          <div class="text-xs text-slate-500 mb-1">Ảnh mới (preview)</div>
          <img id="preview" class="rounded-xl hidden max-h-64 border border-sky-100" alt="preview">
        </div>
      </div>
    </div>

    <div class="flex gap-2">
      <button class="px-5 py-2.5 rounded-xl bg-sky-500 text-white hover:bg-sky-600">Cập nhật</button>
      <a href="{{ route('admin.posts.index') }}" class="px-5 py-2.5 rounded-xl bg-sky-100 text-sky-700 hover:bg-sky-200">Quay lại</a>
    </div>
  </div>
</form>

{{-- STYLE: phóng to editor + font dễ đọc --}}
<style>
  .ck-editor__editable[role="textbox"]{min-height:520px;padding:16px}
  .ck-content{font-size:16px;line-height:1.7}
  /* fallback nếu CDN vẫn chèn banner (trường hợp cache dai dẳng) */
  .ck-powered-by{display:none !important}
</style>

{{-- Preview ảnh mới --}}
<script>
document.querySelector('input[name="image"]').addEventListener('change', e=>{
  const [file] = e.target.files || [];
  if(!file) return;
  const img = document.getElementById('preview');
  img.src = URL.createObjectURL(file);
  img.classList.remove('hidden');
});
</script>


<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js?v=oss"></script>
<script>
(async () => {
  const holder   = document.getElementById('editor-content__holder');
  const textarea = document.getElementById('editor-content');
  const csrf     = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  const editor = await ClassicEditor.create(holder, {
    placeholder: 'Nhập nội dung bài viết ở đây...',
    toolbar: {
      items: [
        'undo','redo','|',
        'heading','|',
        'fontFamily','fontSize','fontColor','fontBackgroundColor','|',
        'bold','italic','underline','strikethrough','removeFormat','|',
        'alignment','outdent','indent','|',
        'bulletedList','numberedList','todoList','|',
        'link','blockQuote','insertTable','imageUpload','horizontalLine'
      ]
    },
    heading: {
      options: [
        { model:'paragraph', title:'Đoạn', class:'ck-heading_paragraph' },
        { model:'heading2',  view:'h2', title:'Tiêu đề 2', class:'ck-heading_heading2' },
        { model:'heading3',  view:'h3', title:'Tiêu đề 3', class:'ck-heading_heading3' }
      ]
    },
    fontFamily: { supportAllValues: true },
    fontSize:   { options: [12,14,16,18,20,24,'default','36'], supportAllValues: true },
    alignment:  { options: ['left','center','right','justify'] },
    table:      { contentToolbar: ['tableColumn','tableRow','mergeTableCells'] },

    /* Upload ảnh trực tiếp */
    simpleUpload: {
      uploadUrl: '{{ route('admin.uploads.ckeditor') }}',
      headers: { 'X-CSRF-TOKEN': csrf }
    },

    /* Loại mọi plugin gây watermark/bản thương mại */
    removePlugins: [
      'LicenseBanner','CloudServices',
      'CKBox','CKFinder','EasyImage',
      'RealTimeCollaborativeComments','RealTimeCollaborativeTrackChanges',
      'RealTimeCollaborativeRevisionHistory','PresenceList',
      'Comments','TrackChanges','TrackChangesData','RevisionHistory',
      'Pagination','WProofreader','MathType','SlashCommand','Template'
    ]
  });

  editor.setData(textarea.value || '');
  const form = textarea.closest('form');
  if (form) form.addEventListener('submit', () => textarea.value = editor.getData());
})();
</script>
@endsection
