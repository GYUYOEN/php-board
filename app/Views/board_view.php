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