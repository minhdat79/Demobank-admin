@extends('layouts.admin')
@section('title','Đăng bài mới')

@section('content')
<form id="postForm" method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data" class="max-w-3xl">
  @csrf
  <div class="bg-white/80 backdrop-blur rounded-2xl border border-sky-100 shadow-sm p-6 space-y-5">
    <div class="text-sm text-slate-500">Tác giả: <b>{{ auth()->user()->name }}</b></div>

    {{-- ===== Tiêu đề ===== --}}
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Tiêu đề</label>
      <input name="title" value="{{ old('title') }}"
             class="w-full rounded-xl border-sky-200 focus:border-sky-500 focus:ring-sky-500" required>
      @error('title')
        <div class="text-rose-600 text-sm mt-1">{{ $message }}</div>
      @enderror
    </div>

    {{-- ===== Nội dung ===== --}}
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Nội dung</label>
      <textarea id="editor-content" name="content" rows="10" class="hidden">{{ old('content') }}</textarea>
      <div id="editor-content__holder" class="min-h-[520px]"></div>
      @error('content')
        <div class="text-rose-600 text-sm mt-1">{{ $message }}</div>
      @enderror
    </div>

    {{-- ===== Ảnh bìa ===== --}}
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Ảnh bìa</label>
      <input type="file" name="image" accept="image/*"
             class="block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl
                    file:border-0 file:text-sm file:font-semibold
                    file:bg-sky-100 file:text-sky-800 hover:file:bg-sky-200">
      @error('image')
        <div class="text-rose-600 text-sm mt-1">{{ $message }}</div>
      @enderror

      <img id="preview" class="mt-3 rounded-xl hidden max-h-64 border border-sky-100" alt="preview">
    </div>

    {{-- ===== Buttons ===== --}}
    <div class="flex gap-2">
      <button type="submit" class="px-5 py-2.5 rounded-xl bg-sky-500 text-white hover:bg-sky-600">
        Đăng bài
      </button>
      <a href="{{ route('admin.posts.index') }}" 
         class="px-5 py-2.5 rounded-xl bg-sky-100 text-sky-700 hover:bg-sky-200">
         Huỷ
      </a>
    </div>
  </div>
</form>

{{-- ===== STYLE: Editor & Font ===== --}}
<style>
  .ck-editor__editable[role="textbox"] { min-height:520px; padding:16px }
  .ck-content { font-size:16px; line-height:1.7 }
  .ck-powered-by { display:none !important }
</style>

{{-- ===== Preview ảnh bìa ===== --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const input = document.querySelector('input[name="image"]');
  if (input) {
    input.addEventListener('change', e => {
      const [file] = e.target.files || [];
      if (!file) return;
      const img = document.getElementById('preview');
      img.src = URL.createObjectURL(file);
      img.classList.remove('hidden');
    });
  }
});
</script>

{{-- ===== CKEditor 5 Classic OSS ===== --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js?v=oss"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
  const holder   = document.getElementById('editor-content__holder');
  const textarea = document.getElementById('editor-content');
  const csrfMeta = document.querySelector('meta[name="csrf-token"]');
  const csrf     = csrfMeta ? csrfMeta.getAttribute('content') : '';

  let editor;

  try {
    editor = await ClassicEditor.create(holder, {
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

      simpleUpload: {
        uploadUrl: '{{ route('admin.uploads.ckeditor') }}',
        headers: { 'X-CSRF-TOKEN': csrf }
      },

      removePlugins: [
        'LicenseBanner','CloudServices','CKBox','CKFinder','EasyImage',
        'RealTimeCollaborativeComments','RealTimeCollaborativeTrackChanges',
        'RealTimeCollaborativeRevisionHistory','PresenceList','Comments',
        'TrackChanges','TrackChangesData','RevisionHistory','Pagination',
        'WProofreader','MathType','SlashCommand','Template'
      ]
    });

    // NẠP dữ liệu ban đầu từ textarea (old('content'))
    if (textarea && textarea.value) {
      editor.setData(textarea.value);
    }

  } catch (err) {
    console.error("CKEditor lỗi:", err);
  }

  // === Ghi lại dữ liệu vào <textarea name="content"> trước khi submit ===
  const form = document.getElementById('postForm');
  if (form) {
    form.addEventListener('submit', () => {
      if (editor && textarea) {
        textarea.value = editor.getData();
      }
    });
  }
});
</script>
@endsection
