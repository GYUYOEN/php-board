<h3 class="pb-4 mb-4 fst-italic border-bottom" style="text-align:center;">
- 게시판 보기 -
</h3>

<article class="blog-post">
    <h2 class="blog-post-title"><?php echo $view->subject; ?></h2>
    <p class="blog-post-meta"><?php echo $view->regdate; ?> by <a href="#"><?php echo $view->userid; ?></a></p>
    <hr>
    <p>
        <?php echo $view->content;?>
    </p>
    <br>
    <?php
        if(isset($view->fs)) {
            $vfs = explode(",",$view->fs);
            foreach($vfs as $img) {
                if(isset($img)){
    ?>
                <img src="<?php echo base_url('/uploads/'.$img);?>">
    <?php }}} ?>
    <hr>
    <p style="text-align:right;">
        <?php
            if($_SESSION['userid']==$view->userid) {
        ?>
        <a href="/modify/<?php echo $view->bid;?>"><button type="button" class="btn btn-primary">수정</button></a>
        <a href="/delete/<?php echo $view->bid;?>"><button type="button" class="btn btn-warning">삭제</button></a>
        <?php }?>
        <a href="/board"><button type="button" class="btn btn-primary">목록</button></a>
    </p> 
</article>
<hr>
<div style="margin-top:30px;">
    <form class="row g-3">
        <input type="hidden" name="file_table_id" id="file_table_id" value="">
        
        <div class="col-md-8">
        <textarea class="form-control" placeholder="댓글을 입력해주세요." id="memo" style="height: 60px"></textarea>
        </div>
        <div class="col-md-2">
        <button type="button" class="btn btn-secondary" id="memo_button">댓글등록</button>
        </div>
        <div class="col-md-2" id="memo_image">
        <div class="btn btn-warning" id="filebutton" onclick="onclick=document.all.upfile.click()">사진첨부</div>
        <input type="file" name="upfile" id="upfile" style="display:none;" />
        </div>
    </form>
</div>
<div id="memo_place">
<?php
if(isset($memoArray)){
    foreach($memoArray as $ma){
?>
    <div class="card mb-4" id="memo_<?php echo $ma->mid?>" style="max-width: 100%;margin-top:20px;">
        <div class="row g-0">
            <div class="col-md-12">
                <div class="card-body">
                    <p class="card-text">
                        <?php
                        if($ma->filename){
                        ?>
                            <img src="/uploads/<?php echo $ma->filename;?>" style="max-width:90%;">
                        <?php }?>
                        <br>  
                        <?php echo $ma->memo;?></p>
                        <p class="card-text">
                            <small class="text-muted">
                                <?php echo $ma->userid;?> / <?php echo $ma->regdate;?>
                            </small>
                        </p>
                        <p class="card-text" style="text-align:right">
                            <button type="button" class="btn btn-secondary btn-sm memo_reply"  mid="<?php echo $ma->mid?>">답글</button>
                            <?php if(isset($_SESSION['userid']) and $_SESSION['userid']==$ma->userid){?>
                                <a href="javascript:;" onclick="memo_modify(<?php echo $ma->mid?>)">
                                    <button type="button" class="btn btn-secondary btn-sm">수정</button>
                                </a>
                                <a href="javascript:;" onclick="memo_del(<?php echo $ma->mid?>)">
                                    <button type="button" class="btn btn-secondary btn-sm">삭제</button>
                                </a>
                            <?php }?>
                        </p>
                    </p>
                </div>
            </div>
        </div>
    </div>
<?php }
    }
    ?>
</div>

<script>
    $("#memo_button").click(function() {
        var file_table_id = $("#file_table_id").val();
        var data = {
            memo: $('#memo').val(),
            bid: <?php echo $view->bid; ?>,
            file_table_id: file_table_id
        };

        if(!data.memo) {
            alert('댓글을 입력하세요.');
            return false;
        }

        $.ajax({
            async: false,
            type: 'post',
            url: '/memo_write',
            data: data,
            dataType: 'html',
            error: function() {},
            success: function(return_data) {
                if(return_data == "login"){
                    alert('로그인 하십시오.');
                    return;
                } else if(return_data=="memo") {
                    alert('댓글을 입력하세요.');
                    return;
                } else {
                    $('#memo').val('')
                    $('#file_table_id').val('')
                    $("#f_"+file_table_id).hide();
                    $("#filebutton").show();
                    $("#memo_place").prepend(return_data);
                }
            }
        })
    })

    $('#upfile').change(function(){
        var files = $('#upfile').prop('files');
        for(var i = 0; i<files.length; i++) {
            attachFile(files[i]);
        }
        $('#upfile').val('');
    })

    function attachFile(file) {
        var formData = new FormData();
        formData.append("savefile", file);
        $.ajax({
            url: '/save_image_memo',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            type: 'POST',
            success: function(return_data) {
                if(return_data.result=='success') {
                    $("#file_table_id").val(return_data.fid);
                    var html = "<div class='col' id='f_"+return_data.fid+"'><div class='card h-100'><img src='/uploads/"+return_data.savename+"' class='card-img-top'><div class='card-body'><button type='button' class='btn btn-warning' onclick='memo_file_del("+return_data.fid+")'>삭제</button></div></div></div>";
                    
                    if(modify_memoid){//댓글 수정시
                        $("#modify_file_table_id").val(return_data.fid);
                        $("#upfile").hide();
                        $("#filebutton_"+modify_memoid).hide();
                        $("#memo_image_"+modify_memoid).html(html);
                    }else{//댓글 입력시
                        $("#file_table_id").val(return_data.fid);
                        $("#upfile").hide();
                        $("#filebutton").hide();
                        $("#memo_image").append(html);
                    }
                } else {
                    if(return_data.data == "login") {
                        alert('로그인 하십시오.');
                        return;
                    }
                }
            }
        })
    }

    function memo_file_del(fid) {
        if(!confirm('삭제하시겠습니까?')) {
            return false;
        }

        var data = {
            fid: fid
        }

        $.ajax({
            async: false,
            type: 'post',
            url: '/memo_file_delete',
            data: data,
            dataType: 'json',
            error: function() {},
            success: function(return_data) {
                if(return_data.result=="no") {
                    alert('삭제하지 못했습니다. 관리자에게 문의하십시오.');
                    return;
                } else {
                    if(modify_memoid){//댓글 수정시 삭제
                        $("#filebutton_"+modify_memoid).show();
                        $("#modify_file_table_id").val('');
                    }else{//댓글 입력시 삭제
                        $("#filebutton").hide();
                        $("#file_table_id").val('');
                    }
                    $("#f_"+fid).hide();
                    $("#upfile").hide();
                }
            }
        })
    }

    function memo_del(mid) {
        if(!confirm('삭제하시겠습니까?')) {
            return false;
        }

        var data = {
            mid: mid
        }

        $.ajax({
            async: false,
            type: 'post',
            url: '/memo_delete',
            data: data,
            dataType: 'json',
            error: function() {},
            success: function(return_data) {
                if(return_data.result=="login") {
                    alert('로그인하십시오.');
                    return;
                } else if(return_data.result=="my") {
                    alert('본인이 작성한 글만 삭제할 수 있습니다.');
                    return;
                } else if(return_data.result=="fail") {
                    alert('삭제하지 못했습니다. 관리자에게 문의하십시오.');
                    return;
                } else if(return_data.result=="nodata") {
                    alert('변수값이 없거나 해당되는 메모가 없습니다.');
                    return;
                } else {
                    $("#memo_"+mid).hide();
                }
            }
        })
    }

    function memo_modify(mid) {
        var data = {
            mid: mid
        }

        $.ajax({
            async: false,
            type: 'post',
            url: '/memo_modify',
            data: data,
            dataType: 'html',
            error: function() {},
            success: function(return_data) {
                if(return_data=="my") {
                    alert('본인이 작성한 글만 수정할 수 있습니다.');
                    return;
                } else {
                    $("#memo_"+mid).html(return_data);
                }
            }
        })
    }

    function memo_modify_update(mid) {
        var modify_file_table_id=$("#modify_file_table_id").val();
        var data = {
            modify_file_table_id: modify_file_table_id,
            memo_text: $("#memo_text_"+mid).val()
        }

        $.ajax({
            async: false,
            type: 'post',
            url: '/memo_modify_update',
            data: data,
            dataType: 'html',
            error: function() {},
            success: function(return_data) {
                if(return_data=="my") {
                    alert('본인이 작성한 글만 수정할 수 있습니다.');
                    return;
                } else {
                    $("#memo_"+mid).html(return_data);
                }
            }
        })
    }
</script>