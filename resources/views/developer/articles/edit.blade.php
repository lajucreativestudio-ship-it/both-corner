<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Artikel "{{ $article->title }}" (Gutenberg Editor) - Both Corner</title>
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
  
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  
  <style>
    body {
      font-family: 'Instrument Sans', sans-serif;
    }
  </style>
</head>
<body class="bg-white text-slate-900 overflow-x-hidden antialiased flex flex-col h-screen">

  <form action="{{ route('developer.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col h-full">
    @csrf

    <!-- Gutenberg Sticky Top Header -->
    <header class="h-16 border-b border-slate-200 px-6 flex items-center justify-between shrink-0 bg-white z-50">
      <div class="flex items-center gap-3">
        <a href="{{ route('developer.dashboard') }}" class="text-slate-500 hover:text-slate-950 p-2 rounded-lg hover:bg-slate-100 transition-colors text-xs font-semibold flex items-center gap-1">
          <span>←</span> Back
        </a>
        <span class="h-4 w-px bg-slate-250"></span>
        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Published</span>
      </div>
      
      <div class="flex items-center gap-2">
        <button type="button" onclick="showToast('💾 Draft otomatis disimpan ke cloud.', true)" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold border border-slate-200 hover:bg-slate-50 text-slate-700 transition-all select-none">
          Save Draft
        </button>
        <button type="submit" class="px-5 py-1.5 rounded-lg text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/10 transition-all select-none cursor-pointer">
          Update
        </button>
      </div>
    </header>

    <!-- Main Editor Window -->
    <div class="flex-1 flex overflow-hidden">
      
      <!-- Left Column: Gutenberg Content Editor -->
      <div class="flex-1 overflow-y-auto px-10 py-12 flex justify-center bg-white">
        <div class="max-w-2xl w-full flex flex-col">
          
          <!-- Large Gutenberg Title -->
          <input type="text" name="title" id="post-title" required value="{{ $article->title }}" placeholder="Add title" oninput="updateSlugAndPreview()" class="text-4xl sm:text-5xl font-extrabold focus:outline-none placeholder-slate-300 bg-transparent w-full border-0 border-b border-transparent focus:border-transparent focus:ring-0 p-0 mb-6 tracking-tight text-slate-900">

          <!-- Gutenberg Block Toolbar -->
          <div class="border border-slate-200 rounded-xl p-1.5 flex items-center gap-1 mb-6 bg-slate-50 shadow-sm select-none">
            <button type="button" onclick="insertBlock('heading')" class="p-2 hover:bg-slate-200 rounded-lg text-xs font-bold text-slate-600 flex items-center gap-1.5 cursor-pointer" title="Add Heading (H2)">
              <span>H2</span>
            </button>
            <button type="button" onclick="insertBlock('paragraph')" class="p-2 hover:bg-slate-200 rounded-lg text-xs font-bold text-slate-600 flex items-center gap-1.5 cursor-pointer" title="Add Paragraph">
              <span>¶</span>
            </button>
            
            <!-- Gutenberg Inline Image Uploader Trigger -->
            <button type="button" onclick="triggerInlineImageUpload()" class="p-2 hover:bg-slate-200 rounded-lg text-xs font-bold text-indigo-600 flex items-center gap-1.5 cursor-pointer" title="Upload & Insert Image">
              <span>🖼 Upload Image</span>
            </button>
            <input type="file" id="inline-image-file" class="hidden" accept="image/*" onchange="uploadInlineImageFile(this)">

            <span class="w-px h-5 bg-slate-200 mx-1"></span>
            <button type="button" onclick="formatText('bold')" class="p-2 hover:bg-slate-200 rounded-lg text-xs font-extrabold text-slate-600 cursor-pointer" title="Bold">B</button>
            <button type="button" onclick="formatText('italic')" class="p-2 hover:bg-slate-200 rounded-lg text-xs italic text-slate-600 cursor-pointer" title="Italic">I</button>
          </div>

          <!-- Gutenberg Borderless Body Content Area -->
          <textarea name="content" id="post-content" required placeholder="Type / to choose a block" class="flex-1 w-full border-0 focus:ring-0 outline-none p-0 resize-none text-slate-700 text-sm sm:text-base leading-relaxed focus:outline-none" style="min-height: 300px;">{{ $article->content }}</textarea>

        </div>
      </div>

      <!-- Right Column: Sidebar Settings -->
      <aside class="w-80 border-l border-slate-200 overflow-y-auto bg-slate-50 flex flex-col shrink-0">
        
        <!-- Sidebar Navigation Tab -->
        <div class="flex border-b border-slate-200 text-xs font-bold select-none shrink-0 bg-white">
          <button type="button" onclick="switchSidebarTab('post')" id="tab-post-btn" class="flex-1 text-center py-3 border-b-2 border-indigo-500 text-indigo-600">Post Settings</button>
          <button type="button" onclick="switchSidebarTab('seo')" id="tab-seo-btn" class="flex-1 text-center py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-900">SEO (Optimize)</button>
        </div>

        <!-- TAB CONTENT: POST SETTINGS -->
        <div id="sidebar-tab-post" class="p-6 space-y-6 block">
          
          <!-- Category selection -->
          <div class="flex flex-col gap-1.5 text-xs">
            <label class="font-bold text-slate-700">Category</label>
            <select name="category" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 text-slate-700 transition-colors">
              @foreach($categories as $cat)
                <option value="{{ $cat->name }}" {{ $article->category === $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
              @endforeach
            </select>
          </div>

          <!-- Featured Image (Upload) -->
          <div class="flex flex-col gap-1.5 text-xs">
            <label class="font-bold text-slate-700">Featured Image</label>
            <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 bg-white flex flex-col items-center justify-center text-center cursor-pointer hover:border-indigo-500 transition-colors relative h-36" onclick="triggerFeaturedImageUpload()">
              <input type="file" name="image" id="featured-image-file" class="hidden" accept="image/*" onchange="previewFeaturedImage(this)">
              <div id="featured-image-placeholder" class="{{ $article->image_url ? 'hidden' : 'space-y-2' }}">
                <span class="text-2xl block">📁</span>
                <span class="block text-[10px] text-slate-500 font-bold">Pilih file gambar unggulan</span>
              </div>
              <img id="featured-image-preview" src="{{ asset($article->image_url) }}" class="{{ $article->image_url ? 'block' : 'hidden' }} max-h-full max-w-full rounded-lg object-contain">
            </div>
            <span class="text-[9px] text-slate-400">Direkomendasikan ukuran landscape 16:9 (maks 2MB).</span>
          </div>

          <!-- WordPress-like Tags Input -->
          <div class="flex flex-col gap-1.5 text-xs border-t border-slate-200 pt-4">
            <label class="font-bold text-slate-700">Tags (Kata Label)</label>
            <div class="flex gap-2">
              <input type="text" id="tag-input" placeholder="Ketik label + tekan Enter" class="flex-1 px-3 py-2 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 text-slate-700 transition-colors" onkeydown="handleTagInput(event)">
              <button type="button" onclick="addTag()" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 transition-colors cursor-pointer">Tambah</button>
            </div>
            <!-- Tags hidden input -->
            <input type="hidden" name="tags" id="tags-hidden-input" value="{{ $article->tags }}">
            <!-- Tags visual list -->
            <div id="tags-container" class="flex flex-wrap gap-1.5 mt-2">
              <!-- Tags render dynamically here -->
            </div>
          </div>

          <!-- Slug / permalink settings -->
          <div class="flex flex-col gap-1.5 text-xs border-t border-slate-200 pt-4">
            <label class="font-bold text-slate-700">URL Slug</label>
            <input type="text" name="slug" id="post-slug" required value="{{ $article->slug }}" placeholder="url-slug-artikel" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 text-slate-700 font-mono transition-colors">
            <span class="text-[10px] text-slate-400">Custom slug URL untuk navigasi SEO friendly.</span>
          </div>

        </div>

        <!-- TAB CONTENT: SEO SETTINGS -->
        <div id="sidebar-tab-seo" class="p-6 space-y-6 hidden">
          
          <!-- Focus keyword -->
          <div class="flex flex-col gap-1.5 text-xs">
            <label class="font-bold text-slate-700">Focus Keyword</label>
            <input type="text" name="focus_keyword" id="seo-keyword" value="{{ $article->focus_keyword }}" placeholder="Contoh: compatible device dslr" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 text-slate-700 transition-colors">
            <span class="text-[10px] text-slate-400">Kata kunci pencarian target utama artikel ini.</span>
          </div>

          <!-- SEO Title -->
          <div class="flex flex-col gap-1.5 text-xs">
            <label class="font-bold text-slate-700">SEO Title</label>
            <input type="text" name="seo_title" id="seo-title" value="{{ $article->seo_title }}" placeholder="Google Search Title Preview" oninput="updateGooglePreview()" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 text-slate-700 transition-colors">
          </div>

          <!-- Meta Description -->
          <div class="flex flex-col gap-1.5 text-xs">
            <label class="font-bold text-slate-700">Meta Description</label>
            <textarea name="meta_description" id="seo-desc" rows="4" placeholder="Deskripsi meta..." oninput="updateGooglePreview()" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white outline-none focus:border-indigo-500 text-slate-700 transition-colors leading-normal">{{ $article->meta_description }}</textarea>
          </div>

          <!-- Google Snippet Visual Preview Card (RankMath Style) -->
          <div class="border border-slate-200 rounded-xl bg-white p-4 shadow-sm text-xs">
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-2">Google Search Preview</span>
            <div class="space-y-1">
              <span class="text-slate-400 text-[10px] block truncate">bothcorner.com > blog > <span id="preview-url-slug" class="font-mono">{{ $article->slug }}</span></span>
              <a href="#" class="text-blue-600 hover:underline text-sm font-semibold leading-tight block" id="preview-seo-title">{{ $article->seo_title ?: $article->title }}</a>
              <p class="text-slate-600 text-[11px] leading-relaxed line-clamp-2" id="preview-seo-desc">{{ $article->meta_description ?: 'Deskripsi meta penelusuran yang disesuaikan untuk search engine optimization...' }}</p>
            </div>
          </div>

        </div>

      </aside>

    </div>

  </form>

  <!-- Gutenberg helper logic -->
  <script>
    // Tab switching in sidebar
    function switchSidebarTab(tabId) {
      document.getElementById('sidebar-tab-post').classList.add('hidden');
      document.getElementById('sidebar-tab-post').classList.remove('block');
      document.getElementById('sidebar-tab-seo').classList.add('hidden');
      document.getElementById('sidebar-tab-seo').classList.remove('block');

      document.getElementById('tab-post-btn').className = "flex-1 text-center py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-900";
      document.getElementById('tab-seo-btn').className = "flex-1 text-center py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-900";

      document.getElementById('sidebar-tab-' + tabId).classList.remove('hidden');
      document.getElementById('sidebar-tab-' + tabId).classList.add('block');
      document.getElementById('tab-' + tabId + '-btn').className = "flex-1 text-center py-3 border-b-2 border-indigo-500 text-indigo-600";
    }

    // Auto slug updates
    function updateSlugAndPreview() {
      const title = document.getElementById('post-title').value;
      const slugInput = document.getElementById('post-slug');
      const seoTitleInput = document.getElementById('seo-title');
      
      const slugified = title.toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)+/g, '');
        
      slugInput.value = slugified;
      
      // Auto-set SEO Title if empty
      if (!seoTitleInput.value) {
        document.getElementById('preview-seo-title').innerText = title || "Google Search Title Preview";
      }

      document.getElementById('preview-url-slug').innerText = slugified || "post-slug";
      updateGooglePreview();
    }

    function updateGooglePreview() {
      const title = document.getElementById('post-title').value;
      const seoTitle = document.getElementById('seo-title').value;
      const seoDesc = document.getElementById('seo-desc').value;
      const slug = document.getElementById('post-slug').value;

      document.getElementById('preview-seo-title').innerText = seoTitle || title || "Google Search Title Preview";
      document.getElementById('preview-seo-desc').innerText = seoDesc || "Deskripsi meta penelusuran yang disesuaikan untuk search engine optimization...";
      document.getElementById('preview-url-slug').innerText = slug || "post-slug";
    }

    // Gutenberg block insertions
    function insertBlock(type) {
      const textarea = document.getElementById('post-content');
      let textToInsert = '';

      if (type === 'heading') {
        textToInsert = '## Heading Block\n';
      } else if (type === 'paragraph') {
        textToInsert = 'Type paragraph block text here...\n';
      }

      const startPos = textarea.selectionStart;
      const endPos = textarea.selectionEnd;
      textarea.value = textarea.value.substring(0, startPos) + textToInsert + textarea.value.substring(endPos, textarea.value.length);
      textarea.focus();
    }

    function formatText(format) {
      const textarea = document.getElementById('post-content');
      const startPos = textarea.selectionStart;
      const endPos = textarea.selectionEnd;
      const selectedText = textarea.value.substring(startPos, endPos);
      
      let formattedText = selectedText;
      if (format === 'bold') {
        formattedText = `**${selectedText}**`;
      } else if (format === 'italic') {
        formattedText = `*${selectedText}*`;
      }

      textarea.value = textarea.value.substring(0, startPos) + formattedText + textarea.value.substring(endPos, textarea.value.length);
      textarea.focus();
    }

    // --- FEATURED IMAGE UPLOAD PREVIEW ---
    function triggerFeaturedImageUpload() {
      document.getElementById('featured-image-file').click();
    }

    function previewFeaturedImage(input) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('featured-image-placeholder').classList.add('hidden');
          const previewImg = document.getElementById('featured-image-preview');
          previewImg.src = e.target.result;
          previewImg.classList.remove('hidden');
          previewImg.classList.add('block');
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    // --- INLINE EDITOR IMAGE UPLOADER ---
    function triggerInlineImageUpload() {
      document.getElementById('inline-image-file').click();
    }

    async function uploadInlineImageFile(input) {
      if (!input.files || !input.files[0]) return;
      
      const file = input.files[0];
      const formData = new FormData();
      formData.append('image', file);
      formData.append('_token', '{{ csrf_token() }}');

      // Feedback to user
      showToast('📤 Sedang mengunggah gambar ke cloud...');

      try {
        const response = await fetch('{{ route('developer.articles.upload-image') }}', {
          method: 'POST',
          body: formData
        });

        const data = await response.json();

        if (data.success) {
          const textarea = document.getElementById('post-content');
          const startPos = textarea.selectionStart;
          const endPos = textarea.selectionEnd;
          const imageMarkdown = `\n![${file.name}](${data.url})\n`;
          
          textarea.value = textarea.value.substring(0, startPos) + imageMarkdown + textarea.value.substring(endPos, textarea.value.length);
          textarea.focus();
          
          showToast('✅ Gambar berhasil diunggah dan disisipkan!', true);
        } else {
          showToast('❌ Gagal mengunggah gambar: ' + (data.message || 'Error'), false);
        }
      } catch (error) {
        showToast('❌ Kesalahan jaringan saat mengunggah.', false);
        console.error(error);
      }
      
      input.value = '';
    }

    // --- WORDPRESS-LIKE TAGS SYSTEM ---
    let tagsList = [];
    const existingTags = '{{ $article->tags }}';
    if (existingTags) {
      tagsList = existingTags.split(',').map(t => t.trim()).filter(t => t !== '');
    }

    function handleTagInput(event) {
      if (event.key === 'Enter') {
        event.preventDefault();
        addTag();
      }
    }

    function addTag() {
      const input = document.getElementById('tag-input');
      const tagValue = input.value.trim().toLowerCase().replace(/[^a-z0-9\s-]+/g, '');
      
      if (tagValue && !tagsList.includes(tagValue)) {
        tagsList.push(tagValue);
        renderTags();
        input.value = '';
      }
    }

    function removeTag(tagToRemove) {
      tagsList = tagsList.filter(t => t !== tagToRemove);
      renderTags();
    }

    function renderTags() {
      const container = document.getElementById('tags-container');
      const hiddenInput = document.getElementById('tags-hidden-input');
      
      container.innerHTML = '';
      tagsList.forEach(tag => {
        container.innerHTML += `
          <span class="inline-flex items-center gap-1 bg-slate-200 text-slate-700 px-2 py-0.5 rounded-md text-[10px] font-semibold select-none">
            #${tag}
            <button type="button" onclick="removeTag('${tag}')" class="text-slate-400 hover:text-slate-600 font-bold ml-0.5">&times;</button>
          </span>
        `;
      });

      hiddenInput.value = tagsList.join(',');
    }

    // Initialize tags on load
    window.onload = function() {
      renderTags();
    };

    // Toast helper
    function showToast(message, isSuccess = true) {
      console.log(message);
    }
  </script>

</body>
</html>
