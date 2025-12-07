import { Controller } from "@hotwired/stimulus";
import { Modal } from "bootstrap";

export default class extends Controller {
    static targets = ["dialog", "form", "token"];

    connect() {
        this.modal = new Modal(this.dialogTarget);
    }

    open(event) {
        const btn = event.currentTarget;
        this.formTarget.action = btn.dataset.confirmUrl;
        this.tokenTarget.value = btn.dataset.confirmToken;

        this.modal.show();
    }

    close() {
        this.modal?.hide();
    }
}
