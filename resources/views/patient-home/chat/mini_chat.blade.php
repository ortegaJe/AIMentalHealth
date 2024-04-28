<div class="modal fade show" id="modalMiniChat" aria-modal="true" role="dialog">
    <div class="modal-dialog chat modal-dialog-scrollable">
        <div class="modal-content chat">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMiniChatTitle">AVi ChatBot</h5>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="messages">
                        <input type="number" id="patient_id" name="patient_id" value="{{ $patient->id }}" hidden>
                        <div class="d-flex flex-row justify-content-start mb-4 left message">
                            <img src="{{ asset('media/icons/avichatbot.png') }}" alt="avatar 1"
                                style="width: 45px; height: 100%;">
                            <div class="p-3 ms-3 ml-2"
                                style="border-radius: 15px; background-color: rgb(200, 255, 244);">
                                <p class="small mb-0" style="color: rgb(38, 109, 107);">
                                    <Strong>¡Hola! {{ $patient->full_name }}</Strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form>
                <div class="card-footer bg-transparent d-flex justify-content-start align-items-center p-3">
                    <img src="{{ asset('media/icons/chatuser.svg') }}" alt="avatar 3"
                        style="width: 40px; height: 100%;">
                    <input type="text" class="form-control form-control-lg" id="message" name="message"
                        autocomplete="off" placeholder="Type message">
                    <button class="ms-1 border-0 bg-transparent ml-2" style="color: royalblue;" title="Enviar">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
