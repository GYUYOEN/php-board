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
        foreach($list as $ls) {
        ?>
        <tr>
            <th scope="row">1</th>
            <td>홍길동</td>
            <td>게시판의 글</td>
            <td>2022.10.26</td>
        </tr>
        <?php } ?>
    </tbody>
</table>