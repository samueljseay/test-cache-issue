document.addEventListener("DOMContentLoaded", function () {
  const { root, nonce } = context;

  const updateButton = document.getElementById('option-update');
  const fetchButton = document.getElementById('option-get-button');
  const optionName = document.getElementById('option-name');
  const loadingIndicator = document.getElementById('option-loading');

  updateButton.onclick = () => {
    const opt = optionName.value;
    const optionSetResult = document.getElementById('option-set-result');

    loadingIndicator.setAttribute('style', 'display:block;');

    updateOption(root, nonce, opt).then((response) => {
      loadingIndicator.setAttribute('style', 'display:none;');
      optionSetResult.innerText = JSON.stringify(response);

      // Show the follow up form
      document.getElementById('get-options').setAttribute('style', 'display:block;');
    })
  }

  fetchButton.onclick = () => {
    const opt = optionName.value;
    const optionGetResult = document.getElementById('option-get-result');

    loadingIndicator.setAttribute('style', 'display:block;');

    fetchOption(root, nonce, opt).then((response) => {
      loadingIndicator.setAttribute('style', 'display:none;');
      optionGetResult.innerText = JSON.stringify(response);
    })
  }
});

const updateOption = function (root, nonce, optionName, value = '1') {
  const data = {
    [optionName]: value
  }


  return fetch(`${root}my_api/opts`, {
    method: 'POST',
    body: JSON.stringify(data),
    headers: {
      'Content-Type': 'application/json',
      'X-WP-Nonce': nonce
    }
  }).then((response) => {
    return response.json();
  })
}


const fetchOption = function (root, nonce, optionName) {
  return fetch(`${root}my_api/opts?options=${optionName}`, {
    headers: {
      'Content-Type': 'application/json',
      'X-WP-Nonce': nonce
    }
  }).then((response) => {
    return response.json();
  })
}


