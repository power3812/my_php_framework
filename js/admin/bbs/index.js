function check_all() {
    let all = document.getElementById('js-select-all');
    let checkboxes = document.getElementsByClassName('js-select');

    for (let i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = all.checked;
    }
}

function check() {
    let all = document.getElementById('js-select-all');
    let checkboxes = document.getElementsByClassName('js-select');

    for (let i = 0; i < checkboxes.length; i++) {
        if (!checkboxes[i].checked) {
            all.checked = false;
            return false;
        }
    }

    all.checked = true;

    return true;
}

function recovery(post_id) {
    let form = document.getElementById('js-form');

    form.post_id.value = post_id;
    form.setAttribute('action', 'recovery.php');
    form.submit();
}

function delete_image(post_id) {
    if (!confirm_delete()) {
        return null;
    }

    let form = document.getElementById('js-form');

    form.post_id.value = post_id;
    form.setAttribute('action', 'deleteImage.php');
    form.submit();
}

function one_delete(post_id) {
    let checkboxes = document.getElementsByClassName('js-select');

    for (let i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].value == post_id) {
            checkboxes[i].checked = true;
        } else {
            checkboxes[i].checked = false;
        }
    }

    bulk_delete();
}

function bulk_delete() {
    let checkboxes = document.getElementsByClassName('js-select');
    let is_checked = false;

    for (let i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            is_checked = true;
            break;
        }
    }

    if (!is_checked) {
        alert('削除する投稿を選択してください');
    } else if (confirm_delete()) {
        let form = document.getElementById('js-form');
        form.setAttribute('action', 'delete.php');
        form.submit();
    }
}

function confirm_delete() {
    if (window.confirm('本当に削除しますか？')) {
        return true;
    }

    return false;
}
