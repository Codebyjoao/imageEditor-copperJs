<!DOCTYPE html>
<html>
<head>
    <title>Crop Image| Cropper JS</title>
    <meta name="_token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha256-WqU1JavFxSAMcLP2WIOI+GB2zWmShMI82mTpLDcqFUg=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" integrity="sha256-jKV9n9bkk/CTP8zbtEtnKaKf+ehRovOYeKoyfthwbC8=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js" integrity="sha256-CgvH7sz3tHhkiVKh05kSUgG97YtzYNnWt6OXcmYzqHY=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
    <link rel="stylesheet" href=".styles.css">
</head>
<style type="text/css">
  img {
      display: block;
      max-width: 100%;
    }
    .preview {
      overflow: hidden;
      width: 160px; 
      height: 160px;
      margin: 10px;
      border: 1px solid red;
    }
    .modal-lg{
      max-width: 1000px !important;
    }

  #reset{
    display: none;
  }

  #return{
    display: none;
  }

</style>
<body>
<div class="container">
    <h1>Image Editor</h1>
    <form method="post">
    <input type="file" name="image"  class="image">
    <input type ="reset" name="reset" id ="reset">
    </form>
</div>

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Image Editor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="img-container">
            <div class="row">
                <div class="col-md-8">
                    <img id="image" src="">
                </div>
                <div class="col-md-4">
                    <div id="preview" class="preview"></div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="crop">Cortar Imagem</button>
        <button type="button" class="btn btn-primary" id="flip-image"> <i class="fa fa-arrows-alt-h"></i></button>
        <button type="button" class="btn btn-primary" id="return"> <i class="fa fa-arrows-alt-h"></i></button>
      </div>
    </div>
  </div>
</div>

</div>
</div>
<script>
  
var $modal = $('#modal');
var image = document.getElementById('image');
var cropper;
var flip;
  
$("body").on("change", ".image", function(e){
    var files = e.target.files;
    var done = function (url) {
      image.src = url;
      $modal.modal('show');
    };
    var reader;
    var file;
    var url;

    if (files && files.length > 0) {
      file = files[0];

      document.getElementById('reset').click()

      if (URL) {
        done(URL.createObjectURL(file));
      } else if (FileReader) {
        reader = new FileReader();
        reader.onload = function (e) {
          done(reader.result);
        };
        reader.readAsDataURL(file);
      }
    }
});

$(document).ready(function(){
 
 $('input').val("");

});

$modal.on('shown.bs.modal', function () {
    cropper = new Cropper(image, {
    aspectRatio: "1.7775",
    viewMode: 3,
    preview: '.preview'
    });
}).on('hidden.bs.modal', function () {
   cropper.destroy();
   cropper = null;
});

$("#crop").click(function(){
    canvas = cropper.getCroppedCanvas({
      width: 1920,
      height: 1080,
    });

    canvas.toBlob(function(blob) {
        url = URL.createObjectURL(blob);
        var reader = new FileReader();
         reader.readAsDataURL(blob); 
         reader.onloadend = function() {
            var base64data = reader.result;  
            
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "upload.php",
                data: {image: base64data},
                success: function(data){
                    console.log(data);
                    $modal.modal('hide');
                    alert("success upload image");
                }
              });
         }
    });
})

// Flip 
function scaleX(){
  cropper.scale(-1, 1);
}

function scaleY(){
  cropper.scale(1, 1);
}

let flipButton = document.getElementById('flip-image')
flipButton.onclick = () => {scaleX(), returnFlipButton.style.display = 'initial', flipButton.style.display = 'none' }

let returnFlipButton = document.getElementById('return')
returnFlipButton.onclick = () => {scaleY(), flipButton.style.display  = 'initial', returnFlipButton.style.display = 'none'}
  
</script>
</body>
</html>