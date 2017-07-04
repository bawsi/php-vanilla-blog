document.getElementById('myArticlesToggle').addEventListener('change', (e) => {
	const checkbox         = e.target;
	const userData         = document.getElementById('loggedInAs').innerText;
	const loggedInUsername = userData.split(': ')[1].split(' (')[0];
	const usersTd          = document.querySelectorAll('td.author');

	// Checkbox is checked, and logged in user has articles. Hide articles of all other authors
	if (checkbox.checked && usersTd) {
		for (let i = 0; i < usersTd.length; i++) {
			const tdUser = usersTd[i];
			const tr     = tdUser.parentNode;

			if (tdUser.textContent !== loggedInUsername) {
				tr.style.display = 'none';
			}
		}
	} else { // Checkbox was unchecked, so show all articles
		for (let i = 0; i < usersTd.length; i++) {
			const tdUser = usersTd[i];
			const tr     = tdUser.parentNode;

			tr.style.display = '';
		}
	}
});