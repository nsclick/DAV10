<?php
class JConfig {
	var $offline = '0';
	var $editor = 'tinymce';
	var $list_limit = '20';
	var $helpurl = 'http://comunidadjoomla.org';
	var $debug = '0';
	var $debug_lang = '0';
	var $sef = '0';
	var $sef_rewrite = '0';
	var $sef_suffix = '0';
	var $feed_limit = '10';
	var $feed_email = 'author';
	var $secret = 'e5pJjoVKN6wL5kQa';
	var $gzip = '0';
	var $error_reporting = '-1';
	var $xmlrpc_server = '0';
	var $log_path = '/var/www/html/portal/logs';
	var $tmp_path = '/var/www/html/portal/tmp';
	var $live_site = '';
	var $force_ssl = '0';
	var $offset = '-4';
	var $caching = '0';
	var $cachetime = '60';
	var $cache_handler = 'file';
	var $memcache_settings = array();
	var $ftp_enable = '0';
	var $ftp_host = '127.0.0.1';
	var $ftp_port = '21';
	var $ftp_user = '';
	var $ftp_pass = '';
	var $ftp_root = '';
	var $dbtype = 'mysql';
	var $host = 'localhost';
	var $user = 'root';
	var $db = 'portal_dev';
	var $dbprefix = 'jos_';
	var $mailer = 'smtp';
	var $mailfrom = 'contacto@davila.cl';
	var $fromname = 'Clíica Dávila';
	var $sendmail = '/usr/sbin/sendmail';
	var $smtpauth = '0';
	var $smtpsecure = 'none';
	var $smtpport = '25';
	var $smtpuser = '';
	var $smtppass = '';
	var $smtphost = 'smtp.davila.cl';
	var $MetaAuthor = '1';
	var $MetaTitle = '1';
	var $lifetime = '15';
	var $session_handler = 'database';
	var $password = 'fc0vvM341osHBpM';
	var $sitename = 'Clínica Dávila';
	var $MetaDesc = 'Clínica Dávila es una institución dedicada a mantener y recuperar integralmente la salud de las personas, procurando entregar la mejor relación entre calidad de atención y costos, para lo cual está en constante innovación en tecnología, gestión, docencia e investigación.';
	var $MetaKeys = 'Clínica Dávila,Clinica Davila,Clínica Davila,Dávila Profesionales,Davila Profesionales,salud,salud de las personas,Broncopulmonar,Cirugía pediátrica,Infectología,Nefrología,Neurología,Genética,Psicodagogía,Neurocirugía,Traumatología,Oncología,Psicología,Otorrinolaringología,Nutrición,Cardiología,Inmunología,Oftalmología,Maxilofacial,Endocrinología,Dermatología,Gastroenterología,salud personas,bienestar,Clínica Dávila y Servicios Médicos S.A,Servicios Médicos,Servicios Medicos,Avda. Recoleta 464,Santiago de Chile,Recoleta 464,innovación en tecnología en la salud,gestión en la salud, docencia e investigación en la salud,Administración Clínica Dávila,Administracion Clinica Davila,Administración Clínica Davila,Clínica Dávila,Clinica Davila,Clínica Davila,Dávila Profesionales,Davila Profesionales,salud,salud de las personas,salud personas,bienestar,Clínica Dávila y Servicios Médicos S.A,Servicios Médicos,Servicios Medicos,Avda. Recoleta 464,Santiago de Chile,Recoleta 464,innovación en tecnología en la salud,gestión en la salud, docencia e investigación en la salud,Clínica Dávila,Clinica Dávila,Clínica Davila,Clinica Davila,Clínica Dávila,Clinica Davila,Clínica Davila,Dávila Profesionales,Davila Profesionales,salud,salud de las personas,salud personas,bienestar,Clínica Dávila y Servicios Médicos S.A,Servicios Médicos,Servicios Medicos,Avda. Recoleta 464,Santiago de Chile,Recoleta 464,innovación en tecnología en la salud,gestión en la salud, docencia e investigación en la salud,Colegio Clínica Dávila,Colegio Clinica Dávila,Colegio Clínica Davila,Colegio Clinica Davila,Clínica Dávila,Clinica Davila,Clínica Davila,Dávila Profesionales,Davila Profesionales,salud,salud de las personas,salud personas,bienestar,Clínica Dávila y Servicios Médicos S.A,Servicios Médicos,Servicios Medicos,Avda. Recoleta 464,Santiago de Chile,Recoleta 464,innovación en tecnología en la salud,gestión en la salud, docencia e investigación en la salud,Empresa Banmédica,Empresa Banmedica,Clínica Dávila,Clinica Davila,Clínica Davila,Dávila Profesionales,Davila Profesionales,salud,salud de las personas,salud personas,bienestar,Clínica Dávila y Servicios Médicos S.A,Servicios Médicos,Servicios Medicos,Avda. Recoleta 464,Santiago de Chile,Recoleta 464,innovación en tecnología en la salud,gestión en la salud, docencia e investigación en la salud,Reseña Histórica Dávila,Reseña Historica Dávila,Reseña Histórica Davila,Reseña Historica Davila,Clínica Dávila,Clinica Davila,Clínica Davila,Dávila Profesionales,Davila Profesionales,salud,salud de las personas,salud personas,bienestar,Clínica Dávila y Servicios Médicos S.A,Servicios Médicos,Servicios Medicos,Avda. Recoleta 464,Santiago de Chile,Recoleta 464,innovación en tecnología en la salud,gestión en la salud, docencia e investigación en la salud,Servicios externos,Servicios,externos,Clínica Dávila,Clinica Davila,Clínica Davila,Dávila Profesionales,Davila Profesionales,salud,salud de las personas,salud personas,bienestar,Clínica Dávila y Servicios Médicos S.A,Servicios Médicos,Servicios Medicos,Avda. Recoleta 464,Santiago de Chile,Recoleta 464,innovación en tecnología en la salud,gestión en la salud, docencia e investigación en la salud,Conozca Dávila,Conozca Davila,Conozca Clínica Davila,Conozca Clinica Davila,Conozca Clínica Dávila,Clínica Dávila,Clinica Davila,Clínica Davila,Dávila Profesionales,Davila Profesionales,salud,salud de las personas,salud personas,bienestar,Clínica Dávila y Servicios Médicos S.A,Servicios Médicos,Servicios Medicos,Avda. Recoleta 464,Santiago de Chile,Recoleta 464,innovación en tecnología en la salud,gestión en la salud, docencia e investigación en la salud,Dermatología,enfermedades de la piel,piel,enfermedades mucosas,enfermedades uñas,enfermedades uña,enfermedades cabello,enfermedades de transmisión sexual,enfermedades de transmision sexual,Endocrinología y Diabetes,Dermatología,Dermatologia,Endocrinologia,Endocrinología,Diabetes,pacientes hospitalizados,pacientes ambulatorios,pacientes críticos,glándulas endocrinas,glandulas endocrinas,tiroides,suprarrenales,hipófisis,gónadas,páncreas,hipofisis,gonadas,pancreas,enfermedades tiroides,enfermedades suprarrenales,enfermedades hipófisis,enfermedades gónadas,enfermedades páncreas,enfermedad tiroide,enfermedad suprarrenal,enfermedad hipófisis,enfermedad gónada,enfermedad páncrea,Enfermedades Cardiovasculares,Cardiovasculares,Cardiovascular,estudios cardiológicos,diagnóstico rápido,diagnóstico oportuno,enfermedades cardiacas,Electrocardiograma,Holter de Arritma,Holter de Presión,Tilt Test,Ergometría,Electrocardiograma,Electrocardiograma de ejercicio,Ecocardiograma 2D Dopler de superficie y transesofágico,Ecocardiograma de estrés con Dobutamina,Ecocardiograma Fetal,Estudio Electrofisiológico y Ablación por radiofrecuencia,Implante de Marcapasos,, Resincronizadores y Desfibriladores,Coronariografías y Angioplastías coronarias,Angioplastías vasculares periféricas,Cintigrafía Miocárdica y Estudios Radioisotópicos de Medicina Nuclear,circulación extracorpórea,Reemplazos valvulares,Corrección de cardiopatías congénitas complejas,Aneurisma aórtico torácico,Operación cardíaca en lactantes,Comunicación interventricula,Puentes aortocoronarios,Aneurisma ventricular,Disección aórtica,Laboratorio de Cardiología,Gastroenterología y Cirugía Digestiva,Gastroenterología,Gastroenterologia,Cirugía Digestiva,Cirugia Digestiva,Cirugia,Digestiva,Departamento de Gastroenterología y Cirugía Digestiva,Departamento de Gastroenterología,Departamento de Cirugía Digestiva,patologías gastrointestinales,Síndrome intestino irritable,Reflujo,Gastroenteritis aguda,Endoscópicos diagnósticos,Endoscópicos terapéuticos,Hemorragias digestivas,Gastroenteritis aguda, Gastroenteritis aguda con deshidratación severa,Ginecología y Obstetricia,Ginecología,Ginecologia,Obstetricia,Departamento de Ginecología y Obstetricia,Departamento de Ginecología,Departamento de Obstetricia,apego,lactancia,salud de la mujer,etapas de la vida,salud mujer,matronas,Salas de Pre-parto,Salas gineco-obstétrica de Urgencia,Sala gineco-obstétrica,Salas gineco obstétrica Urgencia,bebé,bebé antes del nacimiento,nacimiento,Unidad de Pacientes Críticos Adulto y Neonatal,Unidad de Pacientes Críticos Adulto,Neonatal,Oncología Ginecológica,Patología Cervical,cáncer cérvico-uterino,Cirugía laparoscópica,Unidad de Uroginecología,Cirugía Vaginal,Uroginecología y Cirugía Vaginal,Unidad de Cirugía Vaginal,Vaginal,Uroginecología,Hemato Oncología Nefrología y Urología,Hemato,Oncología,Nefrología,Nefrologia,Urologia,Urología,Departamento de Hemato-Oncología,Hemato-Oncología,Departamento de Hemato,Departamento de Oncología,enfermedades oncológicas,quimioterapia,trasplante de médula ósea,unidad de quimioterapia ambulatoria,unidad de quimioterapia,unidad quimioterapia,pacientes inmunodeprimidos,medicamentos anti neoplásicos,medicamentos,anti neoplásicos,especialistas,imágenes,laboratorio, Neurología y Neurocirugía,Neurología,Neurologia,Neurocirugia,Neurocirugía,Departamento de Neurología y Neurocirugía,Departamento de Neurología,Departamento de Neurocirugía,Accidentes vasculares cerebrales y traumatismos,Accidentes vasculares cerebrales,Accidentes traumatismos cerebral,Unidad de Tratamiento del Ataque Cerebral,utac,Ataque Cerebral,malformaciones vasculares encefálicas,Neuroradiología Intervencional,Neuroradiología,Intervencional,aneurismas,accidente vascular,Electroencefalografía,estudios de epilepsia, estudios de sueño completo,estudios de sueño,Electromiografìa digital,Electroencefalografía estándar digital 32 canales,Electroencefalografía,Electroencefalografía con privación de sueño 24 horas,Electroencefalografía con privación parcial de sueño,Electroencefalografía con electrodos nasofaringeos y temporales anteriores,Electroencefalografía más mapeo cerebral,Electroencefalografía con inducción farmacológica,Electroencefalografía más monitoreo de video digital,Test digital de latencias múltiples del sueño,Inducción de crisis epiléptica,Polisomnografía nocturna en adultos,Polisomnografía con titulación de cpap o bipap nasal y buco nasal en adultos,Polisomnografía nocturna pediatrica.Polisomnografía pediatrita más phmetría simultanea nocturna,,cirugía de tumores,cirugía estereotáxica,cirugía vascular,aneurismas,Cirugía de columna,Hernia núcleo pulposo,Estenorraquis lumbar,Instrumentación vertebral,Cirugía de tumores intrarraquídeos,Cirugía de fístulas durales,Cirugía de dolor,implante de estimuladores medulares,Nutrición,Nutricion,Departamento de Nutrición,Departamento Nutrición,patologías nutricionales,orientación nutricional,orientación nutricional embarazadas,orientación nutricional personas, orientación nutricional personas sanas,mejorar sus hábitos de alimentación,hábitos alimenticios,Nutriólogos,Nutriólogos pediatras,Nutricionistas,Sobrepeso,obesidad,dislipidemias,alteración del colesterol,alteración triglicéridos,síndrome metabólico,hábitos alimentarios,actividad física,desnutrición secundaria,cáncer,enfermedad renal,trastornos de absorción intestinal,trastornos del hábito alimentario,anorexia,anorexia nerviosa,bulimia,Balón Intragástrico,Gastroplastía en Manga laparoscópica,Bypass Gástrico laparoscópica,Oftalmología,Oftalmologia,Departamento de Oftalmología,Departamento Oftalmología,enfermedades oculares,alteraciones oculares,médicos oftalmólogos,oftalmólogos,moderno instrumental,Campimetría computarizada Humphey,Perimetría de proyección estática de Goldman,Curva de tensión ambulatoria,Tonómetria aplanática,Estudio sensorio motor de estrabismo,Tratamiento ortóptico,OCT para retina y glaucoma,Test de Teller o de mirada preferencial,Test de sensibilidad de contraste,Test de Schirmer,Diploscopía,Tonometría matinal a domicilio,Topografía Pentacam,Paquimetría Pentacam,Pupilometría,Cataratas con implante de lente intraocular correctores de astigmatismo y de presbicie,Queratocono con implante de anillos intraestromales,Glaucoma,Alteraciones de los párpados,Estrabismo,Cornia y Catarata,Glaucoma,Neuroftalmología,Estrabismo,Retina,Oculoplástica,UveaOncología Pediátrica,Oncología,Oncologia,Pediátrica,Pediatrica,Departamento de Oncología Pediátrica,hemato-oncológicas pediátricas,leucemia,tumores sólidos,hemofilia,oncólogos infantiles,psicólogas infantiles,Servicio de Pediatría,UCI,HEPA,salas de aislamiento,salas de presión positiva,acreditado ante la Superintendencia de Salud,pacientes GES,Parvularia,Otorrino- Laringología,Otorrino,Laringología,Laringologia,urgencias otorrinolaringológicas,otorrinolaringológicas,Departamento de Otorrino,Departamento de Laringología,Departamento de Otorrino – Laringología,pérdida auditiva,Audiometría,Impedanciometría,Rinomanometría,Potenciales evocados auditivos,Octavo par,Maniobras de reposición vestibular,oído,nariz,faringo-laringe,patología del cuello,glándulas salivales,Pérdidas de audición,Otitis,Vértigos,Parálisis facial,Tumores,Disfunciones temporomandibulares,Sinusitis,Rinitis,Pólipos nasales,Desviaciones del tabique nasal,Deformidades de la piramide nasal,Tumores de la región nasal,Epistaxis,Traumatismos nasales,Apneas obstructivas,Tratamiento del ronquido,Amigdalitis,Vegetaciones,Disfonías,Pólipos y nódulos de cuerdas vocales,Tumores de la región faringo - laringea,masas y adenopatías en cuello,patología tumoral e inflamatoria de las glándulas salivares,patología tiroidea,Pediatría y Cirugía infantil,Pediatria,Cirugía infantil,Cirugia infantil,Cirugía,infantil,Cirugia,Broncopulmonar,Cirugía pediátrica,Infectología,Nefrología,Neurología,Genética,Psicopedagogía,Neurocirugía,Traumatología,Oncología,Psicología,Otorrinolaringología,Nutrición,Cardiología,Inmunología,Oftalmología,Maxilofacial,Endocrinología,Dermatología,Gastroenterología,Médicos Pediatras,Cirujanos Pediatras,Neonatólogos,enfermeras especialistas,Psiquiatría y Salud Mental,Psiquiatría,Psiquiatria,Salud Mental,Mental,Salud,Departamento de Psiquiatría y Salud Mental,Departamento de Psiquiatría,Departamento de Salud Mental,trastornos de salud mental,trastornos emocional,psiquiatras,psicólogos,psicoterapéuticos,Respiratorio y Cirugía Torácica,Respiratorio,Cirugía Torácica,Cirugia Toracica,Cirugía Toracica,Cirugía,Cirugia,Torácica,Toracica,Departamento de Respiratorio,Cirugía Torácica,Departamento de Cirugía Torácica,patologías respiratorias,Enfermedades respiratorias agudas,Gripe,faringitis,bronquitis aguda,traqueitis, neumonias,Enfermedades pulmonares crónicas,Enfermedades pulmonares secundarias a tabaco,Espirometría completa (basal y broncodilatador),Medición de volúmenes pulmonares y difusión de monóxido de carbono (DLCO) por Pletismografía,Test de provocación bronquial con metacolina,Test de provocación bronquial con ejercicio,Test cutáneo (inhalantes, alimentos),Test de marcha o caminata de 6 minutos,Oximetría de pulso hospitalizado y ambulatorio,Monitoreo oximétrico nocturno,Fibrobroncoscopía y videobroncoscopía,Biopsias de Pleura,Espirometría completa,basal,broncodilatador,Medición de volúmenes pulmonares y difusión de monóxido de carbono (DLCO) por Pletismografía,Test de provocación bronquial con metacolina,Test de provocación bronquial con ejercicio,Test cutáneo (inhalantes, alimentos),Test de marcha o caminata de 6 minutos,Oximetría de pulso hospitalizado y ambulatorio,Monitoreo oximétrico nocturno,Fibrobroncoscopía y videobroncoscopía,Biopsias de Pleura,Traumatología,Traumatologia,Departamento de Traumatología,Departamento Traumatología,lesiones traumáticas,lesiones congénitas,lesiones adquiridas,terapéuticos,rehabilitación,investigación,Cirugía de la mano,Patología de hombro y codo,Patología de columnaPatología de cadera,Patología de rodilla,Cirugía de pie y tobillo,Tumores músculo - esqueléticos,Terapias con ondas de choque,Cirugía reconstructiva y fijación externa,Equipo de traumatología general,Centro Médico,Centro Medico,Centro,Médico,medico,Servicio de Centro Médico,Servicio Centro Médico,Consultas médicas para adultos,Consultas médicas pediátricas,Entrega de exámenes,Vacunatorio,Sala de yeso,Laboratorios Electroencefalografía,Laboratorios Electromiografía,Laboratorios Otorrinolaringología,Laboratorio Clínico Toma de Muestras,Tratamiento anticoagulante,Consultas médicas para adultos,Laboratorio de Ginecología,Patología Cervical,Medicina Física y Rehabilitación,Unidad de Diálisis,Unidad de Trasplante,Consultas médicas para adultos,Salas de procedimientos- recuperación,Laboratorios Oftalmología,Laboratorios Endoscopía,Laboratorios Respiratorio,Laboratorios Cardiología,Laboratorios Sala Láser,Laboratorios Gastroenterología,Laboratorios Urología,Unidad de Hemato – Oncología,Control Signos Vitales,Curaciones,Nebulizaciones,extracción de Puntos,Tratamiento Intramuscular,Tratamiento Intravenoso,Banco de Sangre,banco, sangre, banco sangre,Donantes de sangre,Donantes sangre,Banco de Sangre de Clínica Dávila,Laboratorio,Servicio de Laboratorio,Servicio Laboratorio,Biología Molecular,Laboratorio Biología Molecular,Toma de Muestra de exámenes,Toma de Muestra,Toma de exámenes,Toma exámenes,Toma examen,Toma de Muestra a domicilio,Toma Muestra domicilio,Muestra domicilio,Medicina Física y Rehabilitación,Medicina Física,Medicina Fisica,,Medicina,Fisica,Rehabilitación,Rehabilitacion,Reabilitación,Reabilitacion,Servicio de Medicina Física y Rehabilitación,Servicio de Medicina Física, Servicio de Rehabilitación,Servicio Rehabilitación,recuperación,recuperación Física,lesiones traumáticas,degenerativas,inflamatorias,neurológicas,cardiovasculares,respiratorias,oncológicas,músculo,esqueléticas,congénitas,médicos fisiatras,kinesiólogos,terapeutas ocupacionales,fonoaudiólogos,auxiliares técnicos,Neonatología,Neonatologia,Servicio de Neonatología,Servicio Neonatología,atención niños recién nacidos,Maternidad,atención recién nacidos,nacimiento,maternal,nacer,nacido,médicos Neonatólogos,matronas,Pabellones,Servicio de Pabellones,Servicio Pabellones,pabellon,Servicios de Anestesia,Servicios de Recuperación,médicos anestesistas,enfermeras,matronas,arsenaleras,Radiología,Radiologia,Servicio de Radiología,Servicio Radiología,Radiología general,Radiología digital,Resonador Magnético,Scanner helicoidal multicorte,Ultrasonido,Mamografía,Densitometría ósea,Radiología intervencional,Radiología digestiva: adulto y pediátrico,Uretrocistografía,Pielografía de eliminación,Medicina nuclear,Hemodinamia,Electrofisiología,Resonancias de cerebro,Resonancias de columna,Resonancias de torax,Resonancias de abdominal,Resonancias de pelviana,Resonancias de extremidades,Resonancias de rodilla,Resonancias de hombro,Resonancias de muñeca,Resonancias de mama,Resonancias de cuerpo entero,Estudios especiales,pilepsia,difusiones cerebrales,perfusion cerebral,estudio de liquido cefalo raquideo,tractografia,colangio,resonancia,vesicula,via biliar,Scanners helicoidales multicorte,Tac de cerebro,Tac de cuello,Tac de silla turca,Tac de orbitas,Tac de cavidades perinasales,Tac de columna,Tac de Cervial,Tac dedorsal,Tac delumbar,Tac de cuerpo entero,Tac de Tórax,Tac deabdomen,Tac de pelvis,Tac de Rodillas,Tac de tobillos,Tac de pies,Tac de codos,Tac de muñecas,Tac de manos,reconstrucciones osteoarticulares tridimensionales,Angio tac,Pielo tac,Urotac,Artro tac,rodilla, hombro,Enteroclisis,Colonoscopía virtual,Mamografía,Mamografías,Ultrasonido mamario,Marcación pre operatorio mamaria,Biopsias mamarias,Core y estereotáxica,Ecotomografías de alta resolución,Doppler Color,Estudios Vasculares,Arterial y Venoso,Ecografías,Abdominales,Pelvianas,Ecografía Músculo,Ecografía esqueléticas,Ecografía hombro,Ecografía caderas,Ecografía codo,Ecografía rodillas,Ecografía muñecas,Ecografía manos,Ecografía Testiculares,Ecografías Intervencionistas,iópsias,Punción,Drenaje,Medicina Nuclear,Gamma Cámara bicabezal con técnica tomográfica,SPECT,Cintigrafía ósea de cuerpo completo,Cintigrafía ósea en tres fases,Cintigrafía ósea con técnica SPECT,Cintigrafía renales,Cintigrafía de tiroides,Cintigrafía de paratiroides,Cintigrafía de glándulas salivales,Cintigrafía SPECT de músculo cardíaco,Cintigrafía SPECT cerebral,Cintigrafía de pulmón,Cintigrafía de reflujo gastroesofágico,Estudio de vaciamiento gástrico,Estudio de motilidad esofágica,Rastreo sistémico con yodo - 131,Cintigrafía Hepatoesplénica,Estudio de divertículo de Meckel,Estudio de hemorragias digestivas,Linfocintigrafías,etección de ganglio centinela,Estudio de infección de origen desconocido,leucocitos marcados,Cintigrafía galio 67,Tratamiento con yodo 131,hipertiroidismo,Tratamiento Sm – 153,tratamiento paliativo del dolor óseo,Urgencia Médico Quirúrgico,urgencia,Urgencia Médico,Urgencia Medico,Médico,quirúrgico,quirurgico,Servicio Médico Quirúrgico,Servicio Quirúrgico,Cardiología,Coloproctología,Cirugía vascular,Cirugía digestiva,Cirugía mamas,Cirugía tórax,Cirugía cabeza y cuello,Cirugía bariátrica,Diabetes,Endocrinología,Fonoaudiología,Geriatría,Gastroenterología,Hemato,Oncología,Hematología,Infectología,Inmunología,Medicina Interna,Maxilofacial,Manejo del dolor,Medicina Física,Rehabilitación,Neurología y Neurocirugía,Nutrición clínica,Nutriología,Nefrología,Urología,Otorrino,Laringología,Oftalmología,Psicopedagogía,Psiquiatría y Psicología,Plástica,Respiratorio,Cirugía Torácica,Reumatología,Trasplantes,Traumatología,Anatomía Patológica,Anatomia,Anatomía,Patológica,Patologica,Unidad Anatomía Patológica,Unidad de Anatomía Patológica,Papanicolaou,método exfoliativo,punciones mamarias,punciones articular,punciones pleural,punciones abdominal,Histopatología,estudio de biopsias,biopsias,biopsias Digestivo,biopsias Respiratorio,biopsias Piel,biopsias Urología,biopsias Ginecología,biopsias Cabeza,biopsias Cuello,Pacientes Críticos,Pacientes,Críticos,Criticos,Unidad de Pacientes Críticos,Unidad Pacientes Críticos,Unidad de Pacientes,Unidad Pacientes,Unidad de Cuidados Intensivos,UCI,Unidad de Tratamiento Intermedio,UTI,Unidad Coronaria,pacientes gravemente enfermos,monitoreo electrocardiográfico,presión invasiva,presión invasiva, saturometría de pulso,capnografía,ventilación mecánica convencional,ventilación mecánica no invasiva,BIPAP,Trasplantes,trasplantes renales,trasplantes pancreáticos,trasplantes hepáticos,trasplante renal,trasplante hepático,trasplante páncrea,trasplante pulmón,trasplante corazón,trasplantes corazón,Vacunatorio,programa nacional de vacunación del Ministerio de Salud,programa nacional de vacunación,Programa Amplio de Inmunización,Programa Inmunización,Programa de Inmunización,Inmunización,vacuna al Nacer,vacuna,vacuna BCG,vacuna Tuberculosis,vacuna Meses,vacuna DPT,vacuna Difteria,vacuna Pertussis,vacuna Tétanos,vacuna HIB,vacuna Haemophilus Influenzae tipo B,vacuna POLIO ORAL,vacuna HEPATITIS B,vacuna TRES VIRICA,vacuna Sarampión,vacuna Paperas,vacuna Rubéola,vacuna DPT,vacuna POLIO ORAL,vacuna DPT,Vacunas complementarias,vacunas Programa Ampliado de Inmunización,vacuna HEPATITIS A Y B,vacuna HEPATITIS A,vacuna HEPATITIS B,vacuna GRIPE,vacuna VARICELA,vacuna INFECCIONES NEUMOCOCICAS,vacuna ROTAVIRUS,Habitaciones,Hospitalización,Hospitalizacion,Individuales,Bipersonales,Hospitalización Clínica,habitaciones Individuales,habitaciones Bipersonales,Requisitos Hospitalización,Requisitos,Hospitalización,Hospitalizacion,habitaciones Individuales,habitaciones Bipersonales,Hospitalización Clínica,Solicitud de ingreso,ingreso paciente,Solicitud de ingreso paciente,Solicitud de ingreso paciente Clínica Dávila,Solicitud de ingreso paciente Clínica,Solicitud de presupuesto,Solicitud de presupuesto médico,Solicitud de presupuesto,Solicitud de presupuesto medico Clínica Dávila,Solicitud de presupuesto Clínica Dávila,Solicitud de presupuesto médico Clínica Dávila,Solicitud de presupuesto Clínica Dávila,Solicitud de presupuesto medico Clínica Dávila,Mi Cuenta, mi cuenta medica, mi cuenta médica Clínica Dávila,Mi Cuenta Clínica Dávila, mi cuenta medica Clínica Dávila, mi cuenta médica Clínica Dávila,Convenio de Accidentes Estudiantiles,convenio,accidentes,estudiantiles,convenio estudiante,convenio salud estudiante,Programas y Convenios, programas médicos, programas medicos,Cobertura ambulatoria en todo Chile,convenios médicos,convenios salud,convenios clínicos,convenio davila,Convenio Enfermedades Catastróficas,convenio enfermedades,convenio catastróficas,convenio catastroficas,enfermedades Catastróficas,enfermedades Catastroficas,Programas y Convenios, programas médicos, programas medicos,convenios médicos,convenios salud,convenios clínicos,convenio davila,Convenio GES,ges,convenio,Programas y Convenios, programas médicos, programas medicos,convenios médicos,convenios salud,convenios clínicos,convenio davila,Convenios Isapres, Particulares y Fonasa,convenio isapre,convenio particulares,convenio fonasa,convenio con todas las isapres,clinica con convenio con todas las isapres,clínica,Programas y Convenios, programas médicos, programas medicos,convenios médicos,convenios salud,convenios clínicos,convenio davila, con convenio con todas las isapres,Cuenta Conocida, convenio,cuenta,conocida,Programas y Convenios, programas médicos, programas medicos,Cobertura ambulatoria en todo Chile,convenios médicos,convenios salud,convenios clínicos,convenio davila,Plan Salud Empresa,convenio salud empresa,salud empresa,plan empresa,salud funcionarios,salud empleados,conevio empleados,conevio salud empleados,Programas y Convenios, programas médicos, programas medicos,Cobertura ambulatoria en todo Chile,convenios médicos,convenios salud,convenios clínicos,convenio davila,Salud Segura Dávila,Salud Segura Davila,Salud,Segura,conevio salud segura,seguro de salud,Programas y Convenios, programas médicos, programas medicos,Cobertura ambulatoria en todo Chile,convenios médicos,convenios salud,convenios clínicos,convenio davila,Reserva Horas, reserva de horas médicas,reservas de horas médicas,reserva de horas medicas,reservas de horas medicas,Resultados Exámenes, examen, examenes,resultado,ver resultados examenes,Solicitar Presupuesto,presupuesto clínico,presupuesto medico,presupuesto médico,solicitud presupuesto médico,solicitar presupuesto médico,Conozca Dávila,Clínica Dávila,Clinica Davila,Clínica Davila,Dávila Profesionales,Davila Profesionales,salud,salud de las personas,salud personas,bienestar,Clínica Dávila y Servicios Médicos S.A,Servicios Médicos,Servicios Medicos,Avda. Recoleta 464,Santiago de Chile,Recoleta 464,innovación en tecnología en la salud,gestión en la salud, docencia e investigación en la salud';
	var $offline_message = 'El sitio está desactivado por tareas de mantenimiento  Por favor, vuelva más tarde.';
}
?>
