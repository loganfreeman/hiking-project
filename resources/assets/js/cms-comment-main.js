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
});
