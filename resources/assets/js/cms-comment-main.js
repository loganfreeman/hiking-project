function increaseLikeCount(element) {
  var x = element.text();
  var count = +x.replace(/\D/g,'') + 1;
  element.text(count + " likes");
}
function decreaseLikeCount(element) {
  var x = element.text();
  var count = +x.replace(/\D/g,'') - 1;
  element.text(count + " likes");
}
$(document).ready(function() {
  cmsCommentEdit();
  cmsCommentModel();
  cmsCommentDelete();
  cmsCommentCreate();
  cmsCommentFetch();
  cmsCommentLock = false;
  $('#like').click(function() {
    var countElement = $('.likesCount', this.closest('.actions'));
    var postId = $(this).data('id');
    $.ajax({
      url: '/post/like',
      type: 'POST',
      dataType: 'JSON',
      data: {
        id: postId
      },
      success: function(response) {
        if(response.hasOwnProperty('deleted_at')) {
          decreaseLikeCount(countElement);
        }else {
          increaseLikeCount(countElement);
        }
      },
      error: function(response) {
        console.log(response);
      }
    })
  });
});
