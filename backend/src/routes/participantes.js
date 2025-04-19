const express = require('express'); 
const router = express.Router(); 
const participantesController = require('../controllers/participantes'); 
 
router.get('/', participantesController.getParticipantes); 
router.get('/:id', participantesController.getParticipanteById); 
router.post('/', participantesController.createParticipante); 
router.put('/:id', participantesController.updateParticipante); 
router.delete('/:id', participantesController.deleteParticipante); 
 
module.exports = router; 
