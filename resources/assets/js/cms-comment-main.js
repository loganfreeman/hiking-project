$(document).ready(function() {
  cmsCommentEdit();
  cmsCommentModel();
  cmsCommentDelete();
  cmsCommentCreate();
  cmsCommentFetch();
  cmsCommentLock = false;
  $('#like').click(function() {
    var postId = $(this).data('id');
    $.ajax({
      url: '/post/like',
      type: 'POST',
      dataType: 'JSON',
      data: {
        id: postId
      },
      success: function(response) {
        console.log(response.message);
      },
      error: function(response) {
        console.log(response);
      }
    })
  });
});
