# 🎓 SISTEMA DE CURSOS ONLINE - BANCO DE DADOS

## 📊 RESUMO DAS MUDANÇAS

O arquivo `projeto_tech_para_modificar_windsurf.sql` foi atualizado com **6 NOVAS TABELAS** para implementar um sistema completo de EAD (Ensino a Distância) com certificados.

---

## 🆕 NOVAS TABELAS CRIADAS

### 1. **`modulos`** 
Organiza o conteúdo dos cursos em módulos temáticos.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id_modulo` | INT (PK) | Identificador único |
| `id_curso` | INT (FK) | Curso ao qual pertence |
| `titulo` | VARCHAR(200) | Nome do módulo |
| `descricao` | TEXT | Descrição do módulo |
| `ordem` | INT | Ordem de exibição |
| `ativo` | TINYINT(1) | Se está ativo |

### 2. **`aulas`**
Contém as aulas de cada módulo (vídeos, PDFs, textos).

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id_aula` | INT (PK) | Identificador único |
| `id_modulo` | INT (FK) | Módulo ao qual pertence |
| `titulo` | VARCHAR(200) | Título da aula |
| `descricao` | TEXT | Descrição da aula |
| `tipo` | ENUM | video, texto, pdf, link |
| `conteudo` | TEXT | URL do vídeo ou conteúdo |
| `duracao_minutos` | INT | Duração estimada |
| `ordem` | INT | Ordem de exibição |

### 3. **`matriculas_online`**
Matrículas dos alunos em cursos online (diferente de turmas físicas).

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id_matricula` | INT (PK) | Identificador único |
| `id_usuario` | INT (FK) | Aluno matriculado |
| `id_curso` | INT (FK) | Curso matriculado |
| `status` | ENUM | ativa, concluida, cancelada |
| `progresso` | DECIMAL(5,2) | Percentual 0-100 |
| `data_conclusao` | DATETIME | Quando concluiu 100% |

### 4. **`progresso_aulas`**
Rastreia quais aulas o aluno já assistiu/concluiu.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id_progresso` | INT (PK) | Identificador único |
| `id_matricula` | INT (FK) | Matrícula relacionada |
| `id_aula` | INT (FK) | Aula concluída |
| `concluida` | TINYINT(1) | Se foi concluída |
| `data_conclusao` | DATETIME | Quando concluiu |
| `tempo_assistido` | INT | Segundos assistidos |

### 5. **`certificados`**
Certificados emitidos ao concluir 100% do curso.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id_certificado` | INT (PK) | Identificador único |
| `id_usuario` | INT (FK) | Aluno que recebeu |
| `id_curso` | INT (FK) | Curso concluído |
| `codigo_validacao` | VARCHAR(50) | Código único para validar |
| `data_emissao` | TIMESTAMP | Data de emissão |
| `carga_horaria` | INT | Horas do curso |

### 6. **`avaliacoes`**
Avaliações dos cursos (1 a 5 estrelas).

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id_avaliacao` | INT (PK) | Identificador único |
| `id_curso` | INT (FK) | Curso avaliado |
| `id_usuario` | INT (FK) | Usuário que avaliou |
| `nota` | INT(1) | 1 a 5 estrelas |
| `comentario` | TEXT | Comentário opcional |

---

## 📦 DADOS DE EXEMPLO INCLUÍDOS

### 🎯 Curso: "Programação Web Completa" (ID: 8)
Curso online com **4 módulos** e **13 aulas**:

#### Módulo 1: Introdução ao Desenvolvimento Web
- ✅ Bem-vindo ao Curso (vídeo - 15min)
- ✅ O que é Desenvolvimento Web? (vídeo - 25min)
- ✅ Ferramentas Necessárias (vídeo - 30min)

#### Módulo 2: HTML5 e CSS3
- ✅ Estrutura HTML (vídeo - 40min)
- ✅ CSS Básico (vídeo - 45min)
- ✅ Flexbox e Grid (vídeo - 50min)
- ✅ Projeto Prático: Landing Page (texto - 60min)

#### Módulo 3: JavaScript Essencial
- ✅ Introdução ao JavaScript (vídeo - 35min)
- ✅ DOM Manipulation (vídeo - 40min)
- ✅ Eventos e Interatividade (vídeo - 35min)

#### Módulo 4: PHP e Banco de Dados
- ✅ Introdução ao PHP (vídeo - 45min)
- ✅ MySQL Básico (vídeo - 40min)
- ✅ Projeto Final: Sistema CRUD (texto - 90min)

**Total: 505 minutos (~8.4 horas)**

---

## 🔧 COMO USAR

### 1️⃣ **Importar no phpMyAdmin:**
```bash
1. Abra o phpMyAdmin (http://localhost/phpmyadmin)
2. Selecione o banco 'projeto_tech'
3. Clique em "Importar"
4. Escolha o arquivo: projeto_tech_para_modificar_windsurf.sql
5. Clique em "Executar"
```

### 2️⃣ **Verificar tabelas criadas:**
```sql
-- Execute no SQL do phpMyAdmin:
SHOW TABLES;

-- Deve exibir as novas tabelas:
-- - modulos
-- - aulas  
-- - matriculas_online
-- - progresso_aulas
-- - certificados
-- - avaliacoes
```

### 3️⃣ **Testar dados de exemplo:**
```sql
-- Ver módulos do curso:
SELECT * FROM modulos WHERE id_curso = 8;

-- Ver aulas do módulo 1:
SELECT * FROM aulas WHERE id_modulo = 1;

-- Ver cursos online disponíveis:
SELECT * FROM curso WHERE tipo_curso = 'Online';
```

---

## 🔗 RELACIONAMENTOS

```
curso (id_curso) 
  └─> modulos (id_curso)
        └─> aulas (id_modulo)

usuario (id_usuario) + curso (id_curso)
  └─> matriculas_online (id_usuario, id_curso)
        └─> progresso_aulas (id_matricula, id_aula)
        └─> certificados (id_matricula)

usuario (id_usuario) + curso (id_curso)
  └─> avaliacoes (id_usuario, id_curso)
```

---

## ✅ REGRAS DE NEGÓCIO IMPLEMENTADAS

1. ✅ Um usuário não pode se matricular 2x no mesmo curso online
2. ✅ Um usuário não pode avaliar 2x o mesmo curso
3. ✅ Um usuário só pode ter 1 certificado por curso
4. ✅ O progresso não pode ser duplicado (única matrícula + aula)
5. ✅ Ao deletar curso, remove módulos e aulas (CASCADE)
6. ✅ Ao deletar usuário, remove matrículas e progresso (CASCADE)

---

## 🚀 PRÓXIMOS PASSOS

Agora você pode criar as páginas PHP para:

### Admin:
- ✏️ Criar/editar módulos
- ✏️ Criar/editar aulas
- 📊 Ver estatísticas de conclusão
- 🎓 Emitir certificados

### Cliente:
- 📚 Ver cursos disponíveis
- ✅ Matricular em curso
- 📹 Assistir aulas
- ✔️ Marcar aulas como concluídas
- 📊 Ver progresso
- 🎓 Baixar certificado (100%)
- ⭐ Avaliar curso

---

## 📝 OBSERVAÇÕES IMPORTANTES

- ⚠️ **Backup:** Faça backup do banco antes de importar!
- ⚠️ **Vídeos:** URLs de exemplo do YouTube (você pode trocar)
- ⚠️ **Cursos físicos:** Continuam funcionando normalmente
- ⚠️ **Turmas:** Sistema de turmas não foi alterado
- ✅ **Compatibilidade:** 100% compatível com sistema atual

---

## 📊 ESTATÍSTICAS DO BANCO

- **Tabelas antigas:** 6
- **Tabelas novas:** 6
- **Total de tabelas:** 12
- **Cursos de exemplo:** 4 (2 físicos + 2 online)
- **Módulos de exemplo:** 4
- **Aulas de exemplo:** 13

---

🎉 **Banco de dados pronto para sistema EAD completo!**
