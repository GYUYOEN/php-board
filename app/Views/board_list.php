<table class="table">
    <thead>
        <tr>
        <th scope="col">번호</th>
        <th scope="col">글쓴이</th>
        <th scope="col">제목</th>
        <th scope="col">등록일</th>
        </tr>
    </thead>
    <tbody id="board_list">
        <?php
        foreach($list as $ls){
        ?>
            <tr>
                <th scope="row"><?php echo $ls->bid;?></th>
                <td><?php echo $ls->userid;?></td>
                <td>
                    <a href="/boardView/<?php echo $ls->bid;?>"><?php echo $ls->subject;?></a>
                </td>
                <td><?php echo $ls->regdate;?></td>
            </tr>
        <?php }?>
    </tbody>
</table>

<p style="text-align:right;">
    <a href="/boardWrite"><button type="button" class="btn btn-primary">등록</button></a>

    <?php
        if(isset($_SESSION['userid'])) {
    ?>
        <a href="/logout"><button type="button" class="btn btn-warning">로그아웃</button></a>
    <?php } else { ?>
            <a href="/login"><button type="button" class="btn btn-warning">로그인</button></a>
    <?php } ?>
</p>