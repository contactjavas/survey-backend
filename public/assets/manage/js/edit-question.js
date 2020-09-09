(function () {
  const elms = {
    questionType: document.querySelector('#question_type_id'),
    choicesWrapper: document.querySelector('.choice-fields')
  };

  function init() {
    checkQuestionType();
    elms.questionType.addEventListener('change', checkQuestionType);
    document.querySelector('.question-choices-panel .plus-button').addEventListener('click', addChoice);

    let minusButtons = document.querySelectorAll('.choice-fields .minus-button');
    minusButtons = minusButtons.length ? minusButtons : [];

    minusButtons.forEach(minusButton => {
      minusButton.addEventListener('click', removeChoice);
    });
  }

  function checkQuestionType() {
    if (elms.questionType.value == 1 || elms.questionType.value == 2) {
      document.querySelector('.question-choices-panel').classList.remove('is-hidden');
      document.querySelector('.add-choice-field').classList.remove('is-hidden');
    } else {
      document.querySelector('.question-choices-panel').classList.add('is-hidden');
      document.querySelector('.add-choice-field').classList.add('is-hidden');
    }
  }

  function addChoice() {
    const timestamp = Date.now();
    const field = elms.choicesWrapper.querySelector('.field');
    let clone = field.cloneNode(true);
    const input = clone.querySelector('input');

    input.id = 'new_question_choice_' + timestamp;
    input.name = 'new_question_choice_' + timestamp;
    input.value = '';
    clone.querySelector('label').htmlFor = 'new_question_choice_' + timestamp;
    
    input.classList.remove('is-filled');
    input.dispatchEvent(new Event('change'));
    
    clone.querySelector('.minus-button').addEventListener('click', removeChoice);
    elms.choicesWrapper.appendChild(clone);
    input.focus();
  }

  function removeChoice() {
    console.log('clicked');
    const fields = document.querySelectorAll('.choice-fields .field');

    if (fields.length > 1) {
      this.parentNode.parentNode.removeChild(this.parentNode);
    }
  }

  init();
})();