function openTab(tabName) {
    // Ẩn tất cả các tab content
    const tabContents = document.getElementsByClassName("tab-content");
    for (let i = 0; i < tabContents.length; i++) {
        tabContents[i].classList.remove("active");
    }

    // Xóa trạng thái active của tất cả các nút tab
    const tabButtons = document.getElementsByClassName("tab-button");
    for (let i = 0; i < tabButtons.length; i++) {
        tabButtons[i].classList.remove("active");
    }

    // Hiển thị tab được chọn và thêm trạng thái active cho nút tương ứng
    document.getElementById(tabName).classList.add("active");
    event.currentTarget.classList.add("active");
}