<!-- MathLive Core & CSS -->
<link rel="stylesheet" href="https://unpkg.com/mathlive/dist/mathlive-static.css" />
<script defer src="https://unpkg.com/mathlive"></script>

<!-- CKEditor 5 DLL & Core Plugins -->
<script src="https://cdn.jsdelivr.net/npm/ckeditor5@41.4.2/build/ckeditor5-dll.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-editor-classic@41.4.2/build/editor-classic.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-essentials@41.4.2/build/essentials.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-basic-styles@41.4.2/build/basic-styles.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-heading@41.4.2/build/heading.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-link@41.4.2/build/link.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-list@41.4.2/build/list.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-block-quote@41.4.2/build/block-quote.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-table@41.4.2/build/table.js"></script>

<!-- Yayure CKEditor 5 Mathlive Plugin (DLL build) -->
<script src="https://cdn.jsdelivr.net/npm/@yayure/ckeditor5-mathlive@41.4.0/build/mathlive.js"></script>

<style>
  /* Styling untuk CKEditor 5 MathLive Plugin */
  .ck-mathlive-panelhook {
    z-index: 1055 !important;
  }
  .ck-mathlive-panel {
    z-index: 1056 !important;
  }
  .ck-math-field math-field {
    cursor: pointer;
  }
</style>
