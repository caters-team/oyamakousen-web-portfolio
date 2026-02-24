class Index {
  constructor() {
    this.handleDialog();
  }

  handleDialog = () => {
    const triggers = Array.from(document.querySelectorAll('.showDialogTrigger'));
    const targets = Array.from(document.querySelectorAll('dialog'));

    if (triggers.length === 0 || targets.length === 0) return;

    triggers.forEach((trigger) => {
      const id = trigger.dataset.dialog ? trigger.dataset.dialog : '';
      trigger.addEventListener('click', () => {
        const target = targets.find(t => t.id === id);
        target.showModal();
      })
    })
  }
}

window.addEventListener('DOMContentLoaded', () => new Index());
