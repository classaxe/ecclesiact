// 1.0.1
/*
Version History:
  1.0.1 (2015-11-29)
    1) Initial release
  1.0.1 (2015-11-29)
    1) Tweak to error message when only one file type is valid
*/

$(function(){
    var ul = $('#upload ul');
    $('#drop a').click(function(){
        // Simulate a click on the file input button
        // to show the file browser dialog
        $(this).parent().find('input').click();
    });
    // Initialize the jQuery File Upload plugin
    $('#upload').fileupload({
        // This element will accept file drag/drop uploading
        dropZone:  $('#drop'),
        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {
            data.maxbytes = parseInt($('#drop').attr('data-max-bytes'));
            data.filetypes = $('#drop').attr('data-file-types').split(',');
            data.ext = data.files[0].name.split('.').pop();
            var tpl = $('<li class="working"><input type="text" value="0" data-width="48" data-height="48"'+
                ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /><p></p><span></span></li>');
            var fileokay = true;
            // Append the file name and file size
            tpl
                .find('p')
                .text(data.files[0].name)
                .append(
                    '<i>' + formatFileSize(data.files[0].size) + '</i><br style="clear:both" />'
                    (data.files[0].size > data.maxbytes || jQuery.inArray(data.ext, data.filetypes)=== -1 ?
                        'ERROR:'
                     :
                            ''
                    ) +
                    (jQuery.inArray(data.ext, data.filetypes)=== -1 ?
                        ' File must be ' + 
                        (data.filetypes.length>1 ? 'one of ' : 'of type ') +
                        data.filetypes.join(', ') + ' &nbsp; '
                     :
                            ''
                    ) +
                    (data.files[0].size > data.maxbytes ?
                            ' Max size is '+formatFileSize(data.maxbytes)
                         :
                             ''
                        ) +
                    '');

            // Add the HTML to the UL element
            data.context = tpl.appendTo(ul);

            // Initialize the knob plugin
            tpl.find('input').knob();

            // Listen for clicks on the cancel icon
            tpl.find('span').click(function(){
                tpl.fadeOut(function(){
                    tpl.remove();
                });
            });
            if (data.files[0].size > data.maxbytes) {
                fileokay = false;
            }
            if (jQuery.inArray(data.ext, data.filetypes)=== -1) {
                fileokay = false;
            }
            if (fileokay) {
                // Automatically upload the file once it is added to the queue
               var jqXHR = data.submit().success(function(result, textStatus, jqXHR){
                    var json = JSON.parse(result);
                    var status = json['status'];
                    if(status == 'error'){
                        data.context.find('p').append('<i>' + json['message'] + '</i>');
                        data.context.addClass('error');
                    }
                });
            }
        },

        progress: function(e, data){

            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            data.context.find('input').val(progress).change();

            if(progress == 100){
                data.context.removeClass('working');
                window.setTimeout(function(){
                    if(window.opener && window.opener.geid('form')){
                        data.context.fadeOut('slow');
                        window.opener.geid('form').submit()
                    }
                    geid('form').submit()
                },
                1000
                );
            }
        },

        fail:function(e, data){
            data.context.addClass('error');
            data.context.removeClass('working');
            data.context.find('input').val(0).change();
            alert(JSON.parse(data.jqXHR.responseText).message);
        }
    });

    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });

    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }
        if (bytes >= 1073741824) {
            return (bytes / 1073741824).toFixed(2) + ' GB';
        }
        if (bytes >= 1048576) {
            return (bytes / 1048576).toFixed(2) + ' MB';
        }
        return (bytes / 1024).toFixed(2) + ' KB';
    }
});
