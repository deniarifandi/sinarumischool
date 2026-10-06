<style>

    /* ============================================================
       COMPACT SPREADSHEET LAYOUT
       ============================================================ */

    .gradebook-screen {
        width: 100%;
        max-width: none;
    }

    .gradebook-screen .tab-content {
        min-width: 0;
    }

    .gradebook-screen #gradebookForm,
    .gradebook-screen #objectiveForm {
        font-size: 0.76rem;
    }

    .gradebook-screen .table-responsive {
        max-height: none !important;
        min-height: 0;
        overflow-x: auto !important;
        overflow-y: visible !important;
        border-color: #b8c2cc !important;
    }

    .gradebook-screen #gradebookTable {
        width: 100%;
        min-width: 100%;
        table-layout: fixed;
        font-size: 0.76rem !important;
        border-color: #c8d0d8;
    }

    .gradebook-screen #gradebookTable th,
    .gradebook-screen #gradebookTable td {
        height: 30px;
        padding: 2px 4px !important;
        white-space: nowrap;
    }

    .gradebook-screen #gradebookTable thead th {
        height: 26px;
        padding: 3px 4px !important;
        background: #edf1f5;
        box-shadow: inset 0 -1px 0 #aeb8c2;
    }

    .gradebook-screen #gradebookTable .student-name-cell {
        width: 165px;
        min-width: 165px !important;
        max-width: 165px !important;
    }

    .gradebook-screen #gradebookTable .religion-cell {
        width: 78px;
        min-width: 78px !important;
    }

    .gradebook-screen #gradebookTable .grade-cell {
        width: 100%;
        min-width: 52px !important;
        height: 24px;
        padding: 1px 2px !important;
        border-radius: 2px;
        font-size: 0.76rem;
    }

    .gradebook-screen #gradebookTable .badge {
        padding: 2px 4px;
        font-size: 0.68rem;
        font-weight: 500;
    }

    .gradebook-screen #gradebookTable tbody tr:nth-child(even) td {
        background-color: #f8fafc;
    }

    .gradebook-screen #gradebookTable tbody tr:nth-child(even) .student-name-cell,
    .gradebook-screen #gradebookTable tbody tr:nth-child(even) .religion-cell {
        background-color: #f8fafc !important;
    }

    .gradebook-screen .form-control:focus,
    .gradebook-screen .form-select:focus {
        border-color: #4f8cff;
        box-shadow: 0 0 0 1px rgba(79, 140, 255, .25) !important;
    }

    @media (max-width: 768px) {
        .gradebook-screen .table-responsive {
            max-height: none !important;
        }

        .gradebook-screen #gradebookTable .student-name-cell {
            width: 135px;
            min-width: 135px !important;
            max-width: 135px !important;
        }

        .gradebook-screen #gradebookTable .religion-cell {
            width: 68px;
            min-width: 68px !important;
        }
    }

    /* ============================================================
       NEON HEADER
       ============================================================ */

    .neon-title {
        background: linear-gradient(90deg, #ffff00 0%, #ffcc00 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow:
            0 0 12px rgba(255, 255, 0, 0.7),
            0 0 25px rgba(255, 255, 0, 0.4);
        font-size: 1.4rem;
        letter-spacing: 0.5px;
    }

    .neon-accent {
        border-left: 5px solid #ffff00;
        padding-left: 15px;
        box-shadow: -5px 0 12px -2px rgba(255, 255, 0, 0.7);
        border-radius: 3px;
    }

    .neon-badge {
        display: inline-block;
        padding: 0.35rem 0.85rem;
        margin-right: 0.4rem;
        margin-top: 0.4rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #fffde7;
        background: rgba(255, 255, 0, 0.12);
        border: 1px solid rgba(255, 255, 0, 0.6);
        border-radius: 50px;
        box-shadow:
            0 0 10px rgba(255, 255, 0, 0.4),
            inset 0 0 5px rgba(255, 255, 0, 0.2);
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    /* ============================================================
       RELIGION MISMATCH
       ============================================================ */

    .religion-disabled td {
        background-color: #e9ecef !important;
        color: #6c757d !important;
    }

    .religion-disabled .student-name-cell,
    .religion-disabled .religion-cell {
        background-color: #e9ecef !important;
        color: #6c757d !important;
    }

    .religion-disabled .grade-cell,
    .religion-disabled .obj-cell {
        background-color: #dfe2e5 !important;
        color: #6c757d !important;
        cursor: not-allowed;
    }

    .religion-disabled .grade-cell:focus,
    .religion-disabled .obj-cell:focus {
        box-shadow: none;
    }

    /* ============================================================
       NORMAL GRADE CELL
       ============================================================ */

    .grade-cell,
    .obj-cell {
        min-width: 65px;
    }

    /* ============================================================
       LOCKED
       ============================================================ */

    .grade-cell[readonly],
    .obj-cell[readonly] {
        cursor: not-allowed;
    }

</style>
