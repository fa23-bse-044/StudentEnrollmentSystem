<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\EvaluationMetric;
use App\Models\User;
use App\Models\Quiz;
use App\Models\Assignment;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    private $curriculum = [
        'BSE' => [
            1 => [
                ['no' => 'CSC101', 'title' => 'Introduction to ICT', 'cr' => 3, 'topics' => 'Hardware Systems, Operating Systems, Networking Concepts, Internet Protocols, Cloud Compute Architecture Fundamentals'],
                ['no' => 'HUM100', 'title' => 'English Comprehension', 'cr' => 3, 'topics' => 'Reading Comprehension, Essay Formulation Frameworks, Vocabulary Synthesis, Professional Sentence Architecture'],
                ['no' => 'MTH100', 'title' => 'Pre-Calculus', 'cr' => 3, 'topics' => 'Functions, Quadratic Graphs, Trigonometric Expressions, Matrices Systems, Limits and Series Derivations'],
                ['no' => 'PHY100', 'title' => 'Mechanics and Waves', 'cr' => 3, 'topics' => 'Vector Kinematics, Newton Laws of Motion, Rotational Inertia, Simple Harmonic Oscillators, Wave Medium Mechanics'],
                ['no' => 'CSC103', 'title' => 'Programming Fundamentals', 'cr' => 4, 'topics' => 'Variables, Control Structures, Arrays, File System Manipulation, Functions Passing, Memory Reference Logic'],
                ['no' => 'HUM110', 'title' => 'Islamic Studies', 'cr' => 3, 'topics' => 'Quranic Exegesis, Life of the Prophet (PBUH), Islamic Legal Structures, Social Ethics Frameworks, Global Jurisprudence'],
                ['no' => 'SWE101', 'title' => 'Introduction to Software Eng.', 'cr' => 3, 'topics' => 'SDLC Life Cycle, Requirement Specification Layouts, UML Design Basics, Functional Verification Processes']
            ],
            2 => [
                ['no' => 'CSC241', 'title' => 'Object Oriented Programming', 'cr' => 4, 'topics' => 'Classes and Instances, Encapsulation Protocols, Polymorphism Engine, Inheritance Hierarchies, Design Interfaces'],
                ['no' => 'MTH105', 'title' => 'Linear Algebra', 'cr' => 3, 'topics' => 'Vector Spaces, Inner Products, Eigenvalues and Eigenvectors, Linear Equations Eliminations, Transformation Formulations'],
                ['no' => 'HUM102', 'title' => 'Report Writing Skills', 'cr' => 3, 'topics' => 'Technical Style Formats, Research Abstract Drafting, Data Vis Standards, Executive Project Summaries Layouts'],
                ['no' => 'CSC291', 'title' => 'Computer Architecture', 'cr' => 3, 'topics' => 'CPU Control Datapaths, ALU Logic Circuits, Instruction Sets Pipelines, Cache Memory Mapping Architectures'],
                ['no' => 'SWE214', 'title' => 'Software Engineering Economics', 'cr' => 3, 'topics' => 'Cost-Benefit Matrix Analysis, Lifecycle Budget Calculations, NPV Assessment, Software Valuation Frameworks'],
                ['no' => 'MTH231', 'title' => 'Calculus & Analytic Geometry', 'cr' => 3, 'topics' => 'Derivatives Optimization, Definitive Integral Calculus, Partial Differentiation, Infinite Sequences Divergence'],
                ['no' => 'HUM112', 'title' => 'Pakistan Studies', 'cr' => 3, 'topics' => 'Historical Ideology, Constitutional Evolution Paths, Geopolitical Strategy Matrix, Economic Development Indicators']
            ],
            3 => [
                ['no' => 'CSC211', 'title' => 'Data Structures & Algorithms', 'cr' => 4, 'topics' => 'Linked Allocation Arrays, Stacks Queues, Balanced Binary Search Trees, Hashing Tables, Sorting Complexity Node'],
                ['no' => 'SWE201', 'title' => 'Software Requirements Eng.', 'cr' => 3, 'topics' => 'Elicitation Engineering Vectors, Use Case Verification, SRS Documentation Specifications, Change Management Tracking'],
                ['no' => 'MTH211', 'title' => 'Discrete Structures', 'cr' => 3, 'topics' => 'Propositional Logics, Set Relations Matrix, Combinatorics Permutations, Graph Theories Graph Algorithms'],
                ['no' => 'SWE205', 'title' => 'Software Architecture & Design', 'cr' => 3, 'topics' => 'Architectural Style Matrices (MVC, SOA), Structural Design Modalities, Component Allocation Layouts'],
                ['no' => 'CSC271', 'title' => 'Database Systems', 'cr' => 4, 'topics' => 'Relational Tuple Models, ER Diagram Configurations, Normalization (1NF to BCNF), SQL Query Compilation, Indices'],
                ['no' => 'HUM210', 'title' => 'Professional Ethics', 'cr' => 3, 'topics' => 'Intellectual Property Laws, ACM IEEE Standards, Cyber Crime Regulation Codes, Engineer Safety Obligations'],
                ['no' => 'MTH375', 'title' => 'Numerical Computing', 'cr' => 3, 'topics' => 'Root Isolation Algorithms, Polynomial Interpolation, Numerical Integration Rules, Error Propagation Modeling']
            ],
            4 => [
                ['no' => 'SWE312', 'title' => 'Software Construction & Dev.', 'cr' => 4, 'topics' => 'Concurrency Management Parallelism, Defensive Code Construction, Refactoring Patterns, Memory Sanitization Tools'],
                ['no' => 'CSC322', 'title' => 'Operating Systems', 'cr' => 4, 'topics' => 'Kernel Process Schedulers, Thread Allocation Matrix, Virtual Memory Page Swaps, File Descriptors, Deadlock Prevention'],
                ['no' => 'SWE302', 'title' => 'Formal Methods in Soft. Eng.', 'cr' => 3, 'topics' => 'Z-Specification Languages, Mathematical Model Verification, State Transition Systems, Theorem Verification Provers'],
                ['no' => 'CSC339', 'title' => 'Computer Networks', 'cr' => 3, 'topics' => 'OSI Stack Reference Nodes, TCP UDP Protocol Handshakes, Routing Routing (BGP, OSPF), IPv4 IPv6 Addressing Systems'],
                ['no' => 'MTH362', 'title' => 'Mathematical Statistics', 'cr' => 3, 'topics' => 'Probability Distributions Modeling, Central Limit Theorem, Hypothesis Testing Vectors, Regression Analyses'],
                ['no' => 'SWE316', 'title' => 'Software Quality Engineering', 'cr' => 3, 'topics' => 'Static Analysis Metrics, Verification vs Validation Frameworks, Automated Testing Scripts, ISO Capability Maturity Models'],
                ['no' => 'HUM320', 'title' => 'Introduction to Sociology', 'cr' => 3, 'topics' => 'Social Interaction Analysis, Cultural Stratification Forces, Structural Institutional Operations, Globalization Dynamics']
            ],
            5 => [
                ['no' => 'SWE321', 'title' => 'Software Project Management', 'cr' => 3, 'topics' => 'Agile Scrum Sprints Execution, COCOMO Cost Estimations, Risk Mitigation Matrices, Critical Path Network Scheduling'],
                ['no' => 'CSC441', 'title' => 'Artificial Intelligence', 'cr' => 4, 'topics' => 'Heuristic State Search Optimization, Knowledge Representation Logics, Neural Classifier Arrays, Expert Reasoning Modules'],
                ['no' => 'SWE333', 'title' => 'Web Engineering Solutions', 'cr' => 4, 'topics' => 'REST API Endpoints Development, Microservices Paradigms, Secure Session Storage Tokenization, Full Stack Deployments'],
                ['no' => 'SWE350', 'title' => 'Software Metrics & Analytics', 'cr' => 3, 'topics' => 'Cyclomatic Complexity Metrics, Function Point Metrics Modeling, Statistical Bug Analysis, Execution Performance Benchmarks'],
                ['no' => 'CSC412', 'title' => 'Big Data Analytics', 'cr' => 3, 'topics' => 'MapReduce Hadoop Parallel Clusters, Stream Processing Engines, NoSQL Bigtable Schema Structures, Data ETL Pipelines'],
                ['no' => 'HUM402', 'title' => 'Organizational Behavior', 'cr' => 3, 'topics' => 'Group Dynamics Management, Conflict Resolution Processes, Leadership Theory Matrix, Corporate Culture Paradigms'],
                ['no' => 'SWE399', 'title' => 'Technical Software Seminar', 'cr' => 3, 'topics' => 'Emerging Architecture Review, Scientific Paper Dissection, Abstract Delivery Matrix, Technical Presentation Delivery']
            ],
            6 => [
                ['no' => 'SWE411', 'title' => 'Software Re-Engineering', 'cr' => 3, 'topics' => 'Legacy Reverse Engineering, Code Smells Diagnostics, Program Restructuring, Code Portability Migration Strategies'],
                ['no' => 'SWE423', 'title' => 'Mobile Application Dev.', 'cr' => 4, 'topics' => 'Cross-Platform Framework States (Flutter Engine), UI Element Lifecycles, Native API Bridges, Storage Caching Nodes'],
                ['no' => 'CSC461', 'title' => 'Cloud Computing Architectures', 'cr' => 3, 'topics' => 'IaaS PaaS SaaS Virtualization, Container Clusters Orchestration (Kubernetes), Serverless Deployments, Cloud Asset IAM'],
                ['no' => 'SWE430', 'title' => 'Agent-Based Software Eng.', 'cr' => 3, 'topics' => 'Multi-Agent Communication Frameworks, Autonomous Decision Elements, Distributed Node Cooperation Architecture'],
                ['no' => 'SWE442', 'title' => 'Design Patterns & Frameworks', 'cr' => 4, 'topics' => 'Enterprise Application Architecture Patterns, Dependency Injection Containers, Aspect Oriented programming Modules'],
                ['no' => 'MGT101', 'title' => 'Introduction to Management', 'cr' => 3, 'topics' => 'Planning Strategy Execution Matrices, Corporate Structures Design, Resource Allocation Models, Performance Control Loops'],
                ['no' => 'SWE401', 'title' => 'Information Security Systems', 'cr' => 3, 'topics' => 'Symmetric Asymmetric Cryptographic Handshakes, Penetration Testing vectors, Threat Architecture Identification Modalities']
            ],
            7 => [
                ['no' => 'SWE491', 'title' => 'Final Year Project I', 'cr' => 3, 'topics' => 'System Architecture Selection, Functional Block Specifications, Prototype Engineering, Initial Verification Validation Logs'],
                ['no' => 'CSC498', 'title' => 'Machine Learning Solutions', 'cr' => 4, 'topics' => 'Feature Scaling Dimensions, Supervised Model Training Optimization, Gradient Descent Variants, Regularization Networks'],
                ['no' => 'SWE455', 'title' => 'Enterprise System Engineering', 'cr' => 3, 'topics' => 'Distributed Transaction Brokers, High Volume Messaging Queues, Message Bus Architecture, Fault Tolerant Fabrics'],
                ['no' => 'SWE462', 'title' => 'User Interface Design', 'cr' => 3, 'topics' => 'Cognitive Load Optimization, High Fidelity Wireframing Models, Usability Analytics, Human Performance Factor Mapping'],
                ['no' => 'CSC425', 'title' => 'Parallel and Distributed Dev.', 'cr' => 4, 'topics' => 'MPI Thread Pools Programming, Shared Memory Synchronization Barriers, Distributed Core Task Scheduling Paradigms'],
                ['no' => 'MGT405', 'title' => 'Entrepreneurship Frameworks', 'cr' => 3, 'topics' => 'SME Capital Structure Calculations, Business Model Canvas Syntheses, Launch Strategy Pitch Metrics'],
                ['no' => 'SWE481', 'title' => 'Software Engineering Ecosystems', 'cr' => 3, 'topics' => 'Continuous Integration Orchestration, Automated Deployment Pipelines, Static Code Scanner Configurations, Artifact Stores']
            ],
            8 => [
                ['no' => 'SWE492', 'title' => 'Final Year Project II', 'cr' => 3, 'topics' => 'Full Scale Implementation Testing, Database Normalization Stress Logs, UI UX Accessibility Reviews, Production Scaling Proofs'],
                ['no' => 'CSC499', 'title' => 'Deep Learning Architectures', 'cr' => 4, 'topics' => 'Convolutional Neural Networks, Recurrent Attention Transfomers, Tensor Backpropagation Backpropagation, Hyperparameter Tuning'],
                ['no' => 'SWE470', 'title' => 'Global Software Development', 'cr' => 3, 'topics' => 'Cross Border Development Synchronization, Distributed Version Tracking, Configuration Management Systems'],
                ['no' => 'SWE475', 'title' => 'Cloud Native Application Dev.', 'cr' => 4, 'topics' => 'Microservice Topology Deployments, Service Mesh Traffic Routing, State Offloading to Cache Arrays, Telemetry Streams'],
                ['no' => 'SWE485', 'title' => 'Virtual Systems Infrastructure', 'cr' => 3, 'topics' => 'Hypervisor Allocation Algorithms, Software Defined Storage Planes, Virtual Network Infrastructure Provisions'],
                ['no' => 'MGT201', 'title' => 'Financial Accounting Overview', 'cr' => 3, 'topics' => 'Double Entry Ledger Processing, Balance Sheet Formulation Models, Cash Flow Analytics Statements, Audit Verification Matrices'],
                ['no' => 'SWE495', 'title' => 'Industrial Project Validation', 'cr' => 3, 'topics' => 'Compliance Metric Checks, Scalability Testing Under Peak Scenarios, Deployment Integrity Verification Checklists']
            ]
        ],
        'BCS' => [
            1 => [
                ['no' => 'CSC101', 'title' => 'Introduction to ICT', 'cr' => 3, 'topics' => 'Hardware Systems, Operating Systems, Networking Concepts, Internet Protocols, Cloud Compute Architecture Fundamentals'],
                ['no' => 'HUM100', 'title' => 'English Comprehension', 'cr' => 3, 'topics' => 'Reading Comprehension, Essay Formulation Frameworks, Vocabulary Synthesis, Professional Sentence Architecture'],
                ['no' => 'MTH100', 'title' => 'Pre-Calculus', 'cr' => 3, 'topics' => 'Functions, Quadratic Graphs, Trigonometric Expressions, Matrices Systems, Limits and Series Derivations'],
                ['no' => 'PHY100', 'title' => 'Mechanics and Waves', 'cr' => 3, 'topics' => 'Vector Kinematics, Newton Laws of Motion, Rotational Inertia, Simple Harmonic Oscillators, Wave Medium Mechanics'],
                ['no' => 'CSC103', 'title' => 'Programming Fundamentals', 'cr' => 4, 'topics' => 'Variables, Control Structures, Arrays, File System Manipulation, Functions Passing, Memory Reference Logic'],
                ['no' => 'HUM110', 'title' => 'Islamic Studies', 'cr' => 3, 'topics' => 'Quranic Exegesis, Life of the Prophet (PBUH), Islamic Legal Structures, Social Ethics Frameworks, Global Jurisprudence'],
                ['no' => 'CSC112', 'title' => 'Discrete Structures Fundamentals', 'cr' => 3, 'topics' => 'Propositional Logics, Set Relations Matrix, Combinatorics Permutations, Graph Theories Graph Algorithms']
            ],
            2 => [
                ['no' => 'CSC241', 'title' => 'Object Oriented Programming', 'cr' => 4, 'topics' => 'Classes and Instances, Encapsulation Protocols, Polymorphism Engine, Inheritance Hierarchies, Design Interfaces'],
                ['no' => 'MTH105', 'title' => 'Linear Algebra', 'cr' => 3, 'topics' => 'Vector Spaces, Inner Products, Eigenvalues and Eigenvectors, Linear Equations Eliminations, Transformation Formulations'],
                ['no' => 'HUM102', 'title' => 'Report Writing Skills', 'cr' => 3, 'topics' => 'Technical Style Formats, Research Abstract Drafting, Data Vis Standards, Executive Project Summaries Layouts'],
                ['no' => 'CSC291', 'title' => 'Computer Architecture', 'cr' => 3, 'topics' => 'CPU Control Datapaths, ALU Logic Circuits, Instruction Sets Pipelines, Cache Memory Mapping Architectures'],
                ['no' => 'CSC215', 'title' => 'Digital Logic System Design', 'cr' => 3, 'topics' => 'Boolean Algebra Reduction, Combinational Logic Circuits, Sequential Registers Counter Triggers, State Machines'],
                ['no' => 'MTH231', 'title' => 'Calculus & Analytic Geometry', 'cr' => 3, 'topics' => 'Derivatives Optimization, Definitive Integral Calculus, Partial Differentiation, Infinite Sequences Divergence'],
                ['no' => 'HUM112', 'title' => 'Pakistan Studies', 'cr' => 3, 'topics' => 'Historical Ideology, Constitutional Evolution Paths, Geopolitical Strategy Matrix, Economic Development Indicators']
            ],
            3 => [
                ['no' => 'CSC211', 'title' => 'Data Structures & Algorithms', 'cr' => 4, 'topics' => 'Linked Allocation Arrays, Stacks Queues, Balanced Binary Search Trees, Hashing Tables, Sorting Complexity Node'],
                ['no' => 'CSC271', 'title' => 'Database Systems', 'cr' => 4, 'topics' => 'Relational Tuple Models, ER Diagram Configurations, Normalization (1NF to BCNF), SQL Query Compilation, Indices'],
                ['no' => 'MTH211', 'title' => 'Multivariate Calculus', 'cr' => 3, 'topics' => 'Multiple Integration Vector Calculus Fields, Gradient Divergence Curl Formulas, Optimization In Multiple Dimensions'],
                ['no' => 'CSC221', 'title' => 'Computer Organization & Assembly', 'cr' => 3, 'topics' => 'X86 Processor Registers, Assembly Instruction Opcode Encodings, Interrupt Handlers, Hardware Port Interfacing Maps'],
                ['no' => 'HUM210', 'title' => 'Professional Ethics', 'cr' => 3, 'topics' => 'Intellectual Property Laws, ACM IEEE Standards, Cyber Crime Regulation Codes, Engineer Safety Obligations'],
                ['no' => 'MTH375', 'title' => 'Numerical Computing', 'cr' => 3, 'topics' => 'Root Isolation Algorithms, Polynomial Interpolation, Numerical Integration Rules, Error Propagation Modeling'],
                ['no' => 'CSC282', 'title' => 'Theory of Automata & Languages', 'cr' => 3, 'topics' => 'Deterministic Finite Automata (DFA), Regular Expression Grammars, Context Free Languages, Turing Computation Limits']
            ],
            4 => [
                ['no' => 'CSC315', 'title' => 'Design & Analysis of Algorithms', 'cr' => 4, 'topics' => 'Dynamic Programming Paradigm, Greedy Splitting Networks, NP-Complete Reduction Formulas, Big-O Boundary Asymptotics'],
                ['no' => 'CSC322', 'title' => 'Operating Systems', 'cr' => 4, 'topics' => 'Kernel Process Schedulers, Thread Allocation Matrix, Virtual Memory Page Swaps, File Descriptors, Deadlock Prevention'],
                ['no' => 'CSC339', 'title' => 'Computer Networks', 'cr' => 3, 'topics' => 'OSI Stack Reference Nodes, TCP UDP Protocol Handshakes, Routing Routing (BGP, OSPF), IPv4 IPv6 Addressing Systems'],
                ['no' => 'SWE301', 'title' => 'Introduction to Software Engineering', 'cr' => 3, 'topics' => 'SDLC Life Cycle, Requirement Specification Layouts, UML Design Basics, Functional Verification Processes'],
                ['no' => 'MTH362', 'title' => 'Mathematical Statistics', 'cr' => 3, 'topics' => 'Probability Distributions Modeling, Central Limit Theorem, Hypothesis Testing Vectors, Regression Analyses'],
                ['no' => 'CSC355', 'title' => 'Compiler Construction Models', 'cr' => 3, 'topics' => 'Lexical Tokens Scanners, Context Free Parser Automata, Intermediate Quad Code Generation, Register Target Optimizers'],
                ['no' => 'HUM320', 'title' => 'Introduction to Sociology', 'cr' => 3, 'topics' => 'Social Interaction Analysis, Cultural Stratification Forces, Structural Institutional Operations, Globalization Dynamics']
            ],
            5 => [
                ['no' => 'CSC441', 'title' => 'Artificial Intelligence', 'cr' => 4, 'topics' => 'Heuristic State Search Optimization, Knowledge Representation Logics, Neural Classifier Arrays, Expert Reasoning Modules'],
                ['no' => 'CSC333', 'title' => 'Web Technologies and Frameworks', 'cr' => 4, 'topics' => 'REST API Endpoints Development, Microservices Paradigms, Secure Session Storage Tokenization, Full Stack Deployments'],
                ['no' => 'CSC412', 'title' => 'Big Data Analytics', 'cr' => 3, 'topics' => 'MapReduce Hadoop Parallel Clusters, Stream Processing Engines, NoSQL Bigtable Schema Structures, Data ETL Pipelines'],
                ['no' => 'CSC385', 'title' => 'Information Security Paradigms', 'cr' => 3, 'topics' => 'Symmetric Asymmetric Cryptographic Handshakes, Penetration Testing vectors, Threat Architecture Identification Modalities'],
                ['no' => 'HUM402', 'title' => 'Organizational Behavior', 'cr' => 3, 'topics' => 'Group Dynamics Management, Conflict Resolution Processes, Leadership Theory Matrix, Corporate Culture Paradigms'],
                ['no' => 'CSC370', 'title' => 'Distributed Database Systems', 'cr' => 3, 'topics' => 'Horizontal Database Fragmentation Partitioning, Two Phase Commit Synchronization, Distributed Locks Resolution'],
                ['no' => 'CSC391', 'title' => 'Advanced Systems Seminar', 'cr' => 3, 'topics' => 'Emerging Architecture Review, Scientific Paper Dissection, Abstract Delivery Matrix, Technical Presentation Delivery']
            ],
            6 => [
                ['no' => 'CSC461', 'title' => 'Cloud Computing Architectures', 'cr' => 3, 'topics' => 'IaaS PaaS SaaS Virtualization, Container Clusters Orchestration (Kubernetes), Serverless Deployments, Cloud Asset IAM'],
                ['no' => 'CSC452', 'title' => 'Digital Image Processing Systems', 'cr' => 4, 'topics' => 'Spatial Convolution Filters, Frequency Domain Transformations Fourier, Image Feature Edge Tracking Segmentations'],
                ['no' => 'MGT101', 'title' => 'Introduction to Management', 'cr' => 3, 'topics' => 'Planning Strategy Execution Matrices, Corporate Structures Design, Resource Allocation Models, Performance Control Loops'],
                ['no' => 'CSC432', 'title' => 'Network Protocols & Design', 'cr' => 3, 'topics' => 'Packet Inspection Metrics, Quality of Service Schedulers, Traffic Shaping Matrices, Custom Protocol Schema Stacks'],
                ['no' => 'CSC448', 'title' => 'Natural Language Processing', 'cr' => 4, 'topics' => 'Tokenization Lemmatization Models, POS Tagging Vectors, Sequence recurrent Neural Models, Transformer Attention Networks'],
                ['no' => 'CSC475', 'title' => 'Human Computer Interaction', 'cr' => 3, 'topics' => 'Cognitive Load Optimization, High Fidelity Wireframing Models, Usability Analytics, Human Performance Factor Mapping'],
                ['no' => 'CSC410', 'title' => 'Data Mining Best Practices', 'cr' => 3, 'topics' => 'Frequent Pattern Rule Generations Association, Clustering Partition Vector Schemas, Anomaly Outlier Detection Runs']
            ],
            7 => [
                ['no' => 'CSC491', 'title' => 'Final Year Project I', 'cr' => 3, 'topics' => 'System Architecture Selection, Functional Block Specifications, Prototype Engineering, Initial Verification Validation Logs'],
                ['no' => 'CSC499', 'title' => 'Deep Learning Architectures', 'cr' => 4, 'topics' => 'Convolutional Neural Networks, Recurrent Attention Transfomers, Tensor Backpropagation Backpropagation, Hyperparameter Tuning'],
                ['no' => 'CSC482', 'title' => 'Quantum Computing Paradigms', 'cr' => 3, 'topics' => 'Qubit Superposition Matrices, Quantum Entanglement Calculations, Shor Grover Search Engine Compilation Models'],
                ['no' => 'MGT405', 'title' => 'Entrepreneurship Frameworks', 'cr' => 3, 'topics' => 'SME Capital Structure Calculations, Business Model Canvas Syntheses, Launch Strategy Pitch Metrics'],
                ['no' => 'CSC435', 'title' => 'High Performance Computing Design', 'cr' => 4, 'topics' => 'SIMD Vector Compute Pipelining, CUDA Core Memory Allocation Hierarchies, Thread Synchronization Profilers'],
                ['no' => 'CSC466', 'title' => 'Wireless and Sensor Networks', 'cr' => 3, 'topics' => 'Ad-Hoc Topology Mesh Protocols, Energy Efficient MAC Schedulers, Signal Attenuation Noise Modeling Metrics'],
                ['no' => 'CSC420', 'title' => 'Advanced Computer Architecture', 'cr' => 3, 'topics' => 'Out of Order Speculative Executions, Superscalar Pipelines Routing, Cache Coherence Directory Fabrics Hardware']
            ],
            8 => [
                ['no' => 'CSC492', 'title' => 'Final Year Project II', 'cr' => 3, 'topics' => 'Full Scale Implementation Testing, Database Normalization Stress Logs, UI UX Accessibility Reviews, Production Scaling Proofs'],
                ['no' => 'CSC495', 'title' => 'Machine Learning Production Dev.', 'cr' => 4, 'topics' => 'Model Serialization Compression pipelines, High Volume Prediction Serving Systems, Continuous Feature Updates drift'],
                ['no' => 'CSC488', 'title' => 'Cryptographic Network Defenses', 'cr' => 3, 'topics' => 'IPSec SSL Tunnel Verification Protocols, Elliptic Curve Distribution Matrices, Zero Knowledge Validation Nodes'],
                ['no' => 'CSC458', 'title' => 'Computer Vision Engineering', 'cr' => 4, 'topics' => 'Object Detection Boundary Anchors, Semantic Segment Nets, Multi Object Optical Flow Trackers, Generative Synthesis'],
                ['no' => 'MGT201', 'title' => 'Financial Accounting Overview', 'cr' => 3, 'topics' => 'Double Entry Ledger Processing, Balance Sheet Formulation Models, Cash Flow Analytics Statements, Audit Verification Matrices'],
                ['no' => 'CSC472', 'title' => 'Bioinformatics Algorithm Tracking', 'cr' => 3, 'topics' => 'DNA Sequence Alignment Matrix Dynamics, Gene Expression Metric Clustering, Protein Secondary Structure Predictors'],
                ['no' => 'CSC490', 'title' => 'System Optimization Final Review', 'cr' => 3, 'topics' => 'Compliance Metric Checks, Scalability Testing Under Peak Scenarios, Deployment Integrity Verification Checklists']
            ]
        ]
    ];

    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->get('tab', 'course_content');
        $students = Student::all();
        $teachers = Teacher::all();

        $selectedStudent = null;
        $studentForCard = null;
        $studentForResult = null;
        $processedMatrix = [];
        $quizzesList = [];
        $assignmentsList = [];
        $cgpa = 0.00;
        $aiAdvice = '';

        $currentStudent = ($user->role === 'student') ? Student::where('student_email', strtolower(trim($user->email)))->first() : null;
        $activeCourseNo = $request->get('active_course', '');

        if ($user->role === 'student' && $currentStudent) {
            $studentForCard = $currentStudent;
        } else {
            if ($request->has('content_student_id')) {
                $studentForCard = Student::find($request->content_student_id);
            } elseif ($request->has('edit_id')) {
                $selectedStudent = Student::find($request->edit_id);
                $studentForCard = $selectedStudent;
            } elseif ($students->count() > 0) {
                $studentForCard = $students->first();
            }
        }

        $coursesForCard = ($studentForCard) ? ($this->curriculum[$studentForCard->department][$studentForCard->semester] ?? []) : [];
        if (empty($coursesForCard) && $user->role === 'faculty') {
            $coursesForCard = $this->curriculum['BSE'][1];
        }

        if (empty($activeCourseNo) && !empty($coursesForCard)) {
            $activeCourseNo = $coursesForCard[0]['no'];
        }

        if ($tab === 'quiz_dashboard') {
            if ($user->role === 'student' && $currentStudent) {
                $quizzesList = Quiz::where('student_id', $currentStudent->id)->where('course_no', $activeCourseNo)->get();
            } else {
                $quizzesList = Quiz::where('course_no', $activeCourseNo)->get();
            }
        }

        if ($tab === 'assignment_dashboard') {
            if ($user->role === 'student' && $currentStudent) {
                $assignmentsList = Assignment::where('course_no', $activeCourseNo)
                                             ->where('last_date', '>=', now())
                                             ->get();
            } else {
                $assignmentsList = Assignment::where('course_no', $activeCourseNo)->get();
            }
        }

        if ($tab === 'result') {
            if ($user->role === 'student') {
                if ($request->has('search_reg') && $request->has('search_email')) {
                    $searchEmail = strtolower(trim($request->search_email));
                    $searchReg = strtoupper(trim($request->search_reg));
                    $targetStudent = Student::where('student_email', $searchEmail)->where('reg_no', $searchReg)->first();

                    if ($targetStudent) {
                        $studentForResult = $targetStudent;
                        $data = $this->compileTranscriptData($targetStudent);
                        $processedMatrix = $data['matrix']; $cgpa = $data['cgpa']; $aiAdvice = $data['aiAdvice'];
                    } else {
                        return redirect()->route('portal.dashboard', ['tab' => 'result'])->with('error', 'Security Failure: The credentials provided do not match your account data parameters.');
                    }
                }
            } else {
                if ($request->has('result_student_id')) {
                    $studentForResult = Student::find($request->result_student_id);
                    if ($studentForResult) {
                        $data = $this->compileTranscriptData($studentForResult);
                        $processedMatrix = $data['matrix']; $cgpa = $data['cgpa']; $aiAdvice = $data['aiAdvice'];
                    }
                }
            }
        }

        // Attach resolved assigned teacher structures cleanly to course items for presentation maps
        foreach ($coursesForCard as $key => $course) {
            $dept = $studentForCard ? $studentForCard->department : 'BSE';
            $sem = $studentForCard ? $studentForCard->semester : 1;

            $assignment = TeacherAssignment::where('course_no', $course['no'])
                                            ->where('department', $dept)
                                            ->where('semester', $sem)
                                            ->first();

            $coursesForCard[$key]['teacher'] = $assignment ? $assignment->teacher : null;
        }

        return view('portal.dashboard', compact(
            'user', 'tab', 'students', 'teachers', 'selectedStudent', 'studentForCard',
            'coursesForCard', 'studentForResult', 'processedMatrix', 'cgpa',
            'aiAdvice', 'activeCourseNo', 'quizzesList', 'assignmentsList', 'currentStudent'
        ));
    }

    public function storeStudent(Request $request) {
        if (Auth::user()->role !== 'faculty') return abort(403);
        $request->validate(['name' => 'required|string', 'student_email' => 'required|email', 'reg_no' => 'required|string', 'semester' => 'required|integer|between:1,8', 'department' => 'required|in:BSE,BCS']);
        if (!preg_match('/^[A-Z]{2}\d{2}-[A-Z]{3}-\d{3}$/', strtoupper($request->reg_no))) { return back()->withInput()->with('error', 'Invalid Registration Number Formatt.'); }

        $regNo = strtoupper(trim($request->reg_no));
        $email = strtolower(trim($request->student_email));

        if ($request->filled('id')) {
            $student = Student::find($request->id);
            if ($student) {
                $student->update([
                    'name' => $request->name,
                    'student_email' => $email,
                    'reg_no' => $regNo,
                    'semester' => $request->semester,
                    'department' => $request->department
                ]);
                return redirect()->route('portal.dashboard', ['tab' => 'course_content'])->with('success', 'Student updated successfully.');
            }
        }

        Student::create([
            'name' => $request->name,
            'student_email' => $email,
            'reg_no' => $regNo,
            'semester' => $request->semester,
            'department' => $request->department
        ]);

        return redirect()->route('portal.dashboard', ['tab' => 'course_content'])->with('success', 'Student processed safely.');
    }

    public function storeTeacher(Request $request)
    {
        if (Auth::user()->role !== 'faculty') return abort(403);
        $request->validate(['name' => 'required|string', 'email' => 'required|email']);

        Teacher::create([
            'name' => $request->name,
            'email' => strtolower(trim($request->email))
        ]);

        return back()->with('success', 'Teacher record introduced safely into database nodes.');
    }

    public function assignTeacher(Request $request)
    {
        if (Auth::user()->role !== 'faculty') return abort(403);
        $request->validate([
            'teacher_id' => 'required|integer',
            'department' => 'required|in:BSE,BCS',
            'semester' => 'required|integer|between:1,8',
            'course_no' => 'required|string'
        ]);

        // Constraint Guard 1: Verify this specific subject does not already have a mapped instructor
        $subjectHasTeacher = TeacherAssignment::where('department', $request->department)
                                              ->where('semester', $request->semester)
                                              ->where('course_no', $request->course_no)
                                              ->exists();
        if ($subjectHasTeacher) {
            return back()->with('error', 'Exception Check: This course subject slot already has an active assigned teacher row.');
        }

        // Constraint Guard 2: Verify this instructor is not already assigned to this semester group
        $teacherBusyInSemester = TeacherAssignment::where('teacher_id', $request->teacher_id)
                                                  ->where('department', $request->department)
                                                  ->where('semester', $request->semester)
                                                  ->exists();
        if ($teacherBusyInSemester) {
            return back()->with('error', 'Exception Core Constraint: A single instructor is restricted from taking two different subjects inside the exact same semester.');
        }

        TeacherAssignment::create($request->all());
        return back()->with('success', 'Instructor mapping relationship compiled successfully.');
    }

    public function saveQuizMark(Request $request)
    {
        if (Auth::user()->role !== 'faculty') return abort(403);
        $request->validate(['student_id' => 'required', 'course_no' => 'required', 'quiz_name' => 'required', 'marks' => 'required|integer']);

        Quiz::create([
            'student_id' => $request->student_id,
            'course_no' => $request->course_no,
            'quiz_name' => $request->quiz_name,
            'marks' => $request->marks
        ]);

        return back()->with('success', 'Quiz score record added successfully.');
    }

    public function deleteQuiz($id)
    {
        if (Auth::user()->role !== 'faculty') return abort(403);
        Quiz::findOrFail($id)->delete();
        return back()->with('success', 'Selected quiz record purged.');
    }

    public function saveAssignment(Request $request)
    {
        if (Auth::user()->role !== 'faculty') return abort(403);
        $request->validate([
            'course_no' => 'required',
            'title' => 'required',
            'start_date' => 'required',
            'last_date' => 'required',
            'question_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120'
        ]);

        $data = $request->all();

        if ($request->hasFile('question_file')) {
            $file = $request->file('question_file');
            $fileName = 'question_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/questions'), $fileName);
            $data['question_file'] = 'uploads/questions/' . $fileName;
        }

        Assignment::create($data);
        return back()->with('success', 'Assignment published with question file successfully.');
    }

    public function deleteAssignment($id)
    {
        if (Auth::user()->role !== 'faculty') return abort(403);
        Assignment::findOrFail($id)->delete();
        return back()->with('success', 'Assignment blueprint cleared from database.');
    }

    public function studentSubmitAssignment(Request $request, $id)
    {
        if (Auth::user()->role !== 'student') return abort(403);

        $student = Student::where('student_email', Auth::user()->email)->firstOrFail();
        $parentAssignment = Assignment::findOrFail($id);

        $alreadySubmitted = Assignment::where('student_id', $student->id)
                                      ->where('title', $parentAssignment->title)
                                      ->where('course_no', $parentAssignment->course_no)
                                      ->exists();

        if ($alreadySubmitted) {
            return back()->with('error', 'Exception: You have already uploaded this assignment instance.');
        }

        $request->validate([
            'submission_file' => 'required|file|mimes:pdf,doc,docx|max:5120'
        ]);

        if ($request->hasFile('submission_file')) {
            $file = $request->file('submission_file');
            $fileName = 'submission_' . $student->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/assignments'), $fileName);

            Assignment::create([
                'course_no' => $parentAssignment->course_no,
                'title' => $parentAssignment->title,
                'description' => 'Uploaded',
                'start_date' => $parentAssignment->start_date,
                'last_date' => $parentAssignment->last_date,
                'student_id' => $student->id,
                'file_path' => 'uploads/assignments/' . $fileName
            ]);

            return back()->with('success', 'Assignment file uploaded successfully.');
        }

        return back()->with('error', 'File processing exception.');
    }

    public function facultyGradeAssignment(Request $request, $id)
    {
        if (Auth::user()->role !== 'faculty') return abort(403);
        $request->validate(['marks' => 'required|integer']);

        $assignment = Assignment::findOrFail($id);
        $assignment->update(['marks' => $request->marks]);

        return back()->with('success', 'Assignment score assigned safely.');
    }

    public function saveMarks(Request $request, $id) {
        if (Auth::user()->role !== 'faculty') return abort(403);
        $student = Student::findOrFail($id);
        if ($request->has('marks')) {
            foreach ($request->marks as $courseNo => $markVal) {
                if ($markVal !== null && $markVal !== '') {
                    EvaluationMetric::updateOrCreate(['student_id' => $student->id, 'course_no' => $courseNo], ['marks' => (int)$markVal]);
                }
            }
        }
        return redirect()->route('portal.dashboard', ['tab' => 'registration_card', 'card_student_id' => $student->id])->with('success', 'Academic performance parameters updated successfully.');
    }

    public function destroyStudent($id) {
        if (Auth::user()->role !== 'faculty') return abort(403);
        $student = Student::findOrFail($id);
        $student->delete();
        return redirect()->route('portal.dashboard', ['tab' => 'course_content'])->with('success', 'Student record dropped safely.');
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $fileName = 'profile_' . $user->id . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/profiles'), $fileName);

            if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
                @unlink(public_path($user->profile_picture));
            }

            $user->profile_picture = 'uploads/profiles/' . $fileName;
            User::find($user->id)->update(['profile_picture' => $user->profile_picture]);

            // Sync with teachers table if active logged in user belongs to an instructor profile link
            $teacher = Teacher::where('email', strtolower(trim($user->email)))->first();
            if ($teacher) {
                $teacher->update(['profile_picture' => $user->profile_picture]);
            }

            return back()->with('success', 'Profile image asset updated successfully.');
        }

        return back()->with('error', 'Image uploading failure.');
    }

    private function compileTranscriptData($student) {
        $courses = $this->curriculum[$student->department][$student->semester] ?? [];
        $totalCredits = 0; $totalWeightedPoints = 0; $processedMatrix = [];
        foreach ($courses as $c) {
            $metric = EvaluationMetric::where('student_id', $student->id)->where('course_no', $c['no'])->first();
            $marks = $metric ? $metric->marks : null;
            if ($marks !== null) {
                $gradeData = $this->calculateGrade($marks); $totalCredits += $c['cr']; $totalWeightedPoints += ($gradeData['gp'] * $c['cr']);
                $processedMatrix[] = array_merge($c, ['marks' => $marks], $gradeData);
            } else { $processedMatrix[] = array_merge($c, ['marks' => 'N/A', 'lg' => 'I', 'gp' => 0.00]); }
        }
        $cgpa = $totalCredits > 0 ? round($totalWeightedPoints / $totalCredits, 2) : 0.00;

        if ($cgpa >= 3.70) {
            $aiAdvice = "Elite academic momentum verified. Student is tracking optimal engineering velocity paradigms.";
        } elseif ($cgpa >= 3.00) {
            $aiAdvice = "Commendable structural competency profiles active. Maintain consistency across advanced tracks.";
        } elseif ($cgpa >= 2.00) {
            $aiAdvice = "Stable tracking matrix. Targeted diagnostic focus recommended on coding credit intensive tracks.";
        } else {
            $aiAdvice = "CRITICAL ACTION WARNING: Academic velocity drop detected. Core remediation metrics required immediately.";
        }

        return ['matrix' => $processedMatrix, 'cgpa' => $cgpa, 'aiAdvice' => $aiAdvice];
    }

    private function calculateGrade($marks) {
        if ($marks >= 85) return ['lg' => 'A', 'gp' => 4.00]; if ($marks >= 80) return ['lg' => 'A-', 'gp' => 3.66];
        if ($marks >= 75) return ['lg' => 'B+', 'gp' => 3.33]; if ($marks >= 70) return ['lg' => 'B', 'gp' => 3.00];
        if ($marks >= 65) return ['lg' => 'B-', 'gp' => 2.66]; if ($marks >= 60) return ['lg' => 'C+', 'gp' => 2.33];
        if ($marks >= 55) return ['lg' => 'C', 'gp' => 2.00]; if ($marks >= 50) return ['lg' => 'C-', 'gp' => 1.66];
        if ($marks >= 45) return ['lg' => 'D', 'gp' => 1.33]; return ['lg' => 'F', 'gp' => 0.00];
    }
}
